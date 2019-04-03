<?php

namespace Psonic\Concretes\Channels;

use Psonic\Concretes\Commands\Command;
use Psonic\Concretes\Commands\Ingest\PushCommand;
use Psonic\Concretes\Commands\Ingest\StartIngestChannelCommand;
use Psonic\Contracts\Client;
use Psonic\Contracts\Response;

class Ingest extends Channel
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function send(Command $command): Response
    {
        return $this->client->send($command);
    }

    public function connect()
    {
        parent::connect();
        $response = $this->send(new StartIngestChannelCommand);
        if($buffersize = $response->get('bufferSize')){
            $this->bufferSize = $buffersize;
        }
        return $this;
    }

    public function push(string $collection, string $bucket, string $object, string $text)
    {
        $command = new PushCommand($collection, $bucket, $object, $text);
        return $this->send($command);
    }
}