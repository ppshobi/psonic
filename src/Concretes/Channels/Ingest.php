<?php

namespace Psonic\Concretes\Channels;

use Psonic\Concretes\Commands\Command;
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
        $this->send(new StartIngestChannelCommand);
        $this->client->clearBuffer();
        return $this;
    }
}