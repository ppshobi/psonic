<?php

namespace Psonic;


use Psonic\Channels\Channel;
use Psonic\Contracts\Client;
use Psonic\Commands\Search\QueryCommand;
use Psonic\Commands\Search\SuggestCommand;
use Psonic\Exceptions\CommandFailedException;
use Psonic\Commands\Search\StartSearchChannelCommand;

class Search extends Channel
{
    /**
     * Search Channel constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    /**
     * @return mixed|Contracts\Response|void
     * @throws Exceptions\ConnectionException
     */
    public function connect($password = 'SecretPassword')
    {
        parent::connect();

        $response = $this->send(new StartSearchChannelCommand($password));

        if ($bufferSize = $response->get('bufferSize')) {
            $this->bufferSize = (int)$bufferSize;
        }

        return $response;
    }

    /**
     * @param $collection
     * @param $bucket
     * @param $terms
     * @param $limit
     * @param $offset
     * @param $locale
     * @return array
     * @throws CommandFailedException
     */
    public function query($collection, $bucket, $terms, $limit = null, $offset = null, $locale = null): array
    {
        $response = $this->send(new QueryCommand($collection, $bucket, $terms, $limit, $offset, $locale));

        if (!$response->getStatus() == 'PENDING') {
            throw new CommandFailedException;
        }

        $results = $this->read();

        if (!$results->getStatus() == 'EVENT') {
            throw new CommandFailedException;
        }

        return $results->getResults();
    }

    /**
     * @param $collection
     * @param $bucket
     * @param $terms
     * @param $limit
     * @return array
     * @throws CommandFailedException
     */
    public function suggest($collection, $bucket, $terms, $limit = null): array
    {
        $response = $this->send(new SuggestCommand($collection, $bucket, $terms, $limit));

        if (!$response->getStatus() == 'PENDING') {
            throw new CommandFailedException;
        }

        $results = $this->read();

        if (!$results->getStatus() == 'EVENT') {
            throw new CommandFailedException;
        }

        return $results->getResults();
    }
}
