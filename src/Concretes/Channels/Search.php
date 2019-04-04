<?php

namespace Psonic\Concretes\Channels;


use Psonic\Concretes\Commands\Search\QueryCommand;
use Psonic\Concretes\Commands\Search\StartSearchChannelCommand;
use Psonic\Concretes\Commands\Search\SuggestCommand;
use Psonic\Contracts\Client;
use Psonic\Contracts\Command;
use Psonic\Contracts\Response;
use Psonic\Exceptions\CommandFailedException;

class Search extends Channel
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function connect()
    {
        parent::connect();
        $this->client->clearBuffer();

        $response = $this->send(new StartSearchChannelCommand);

        if($bufferSize = $response->get('bufferSize')){
            $this->bufferSize = (int) $bufferSize;
        }

        return $response;
    }

    public function query($collection, $bucket, $terms): array
    {
        $response = $this->send(new QueryCommand($collection, $bucket, $terms));

        if(! $response->getStatus() == 'PENDING') {
            throw new CommandFailedException;
        }

        $results = $this->read();

        if(! $results->getStatus() == 'EVENT') {
            throw new CommandFailedException;
        }

        return $results->getResults();
    }

    public function suggest($collection, $bucket, $terms): array
    {
        $response = $this->send(new SuggestCommand($collection, $bucket, $terms));

        if(! $response->getStatus() == 'PENDING') {
            throw new CommandFailedException;
        }

        $results = $this->read();

        if(! $results->getStatus() == 'EVENT') {
            throw new CommandFailedException;
        }

        return $results->getResults();
    }
}