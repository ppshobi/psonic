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
    public function connect(string $password = 'SecretPassword')
    {
        parent::connect();

        $response = $this->send(new StartSearchChannelCommand($password));


        /** @var string $bufferSize */
        $bufferSize = $response->get('bufferSize');

        if ($bufferSize) {
            $this->bufferSize = (int)$bufferSize;
        }

        return $response;
    }


    /**
     *@return array<mixed>
     */
    public function query(string $collection,string $bucket,string $terms,int $limit = null,int $offset = null,string $locale = null): array
    {
        $response = $this->send(new QueryCommand($collection, $bucket, $terms, $limit, $offset, $locale));

        if (!$response->getStatus() == 'PENDING') {
            throw new CommandFailedException;
        }

        /** @var SonicResponse $results */
        $results = $this->read();

        if (!$results->getStatus() == 'EVENT') {
            throw new CommandFailedException;
        }

        return $results->getResults();
    }


    /**
     *@return array<mixed>
     */
    public function suggest(string $collection,string $bucket,string $terms, int $limit = null): array
    {
        $response = $this->send(new SuggestCommand($collection, $bucket, $terms, $limit));

        if (!$response->getStatus() == 'PENDING') {
            throw new CommandFailedException;
        }

        /** @var SonicResponse $results */
        $results = $this->read();

        if (!$results->getStatus() == 'EVENT') {
            throw new CommandFailedException;
        }

        return $results->getResults();
    }
}
