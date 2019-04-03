<?php

namespace Psonic\Concretes\Channels;

use Psonic\Concretes\Commands\Command;
use Psonic\Concretes\Commands\Ingest\CountCommand;
use Psonic\Concretes\Commands\Ingest\PopCommand;
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
            $this->bufferSize = (int) $buffersize;
        }
        return $this;
    }

    public function push(string $collection, string $bucket, string $object, string $text)
    {
        $chunks = $this->splitString($text);

        foreach ($chunks as $chunk) {
            $message = $this->send(new PushCommand($collection, $bucket, $object, $chunk));
            if($message == false || $message == "") {
                throw new \InvalidArgumentException();
            }
        }
        return $message;
    }

    public function pop(string $collection, string $bucket, string $object, string $text)
    {
        $chunks = $this->splitString($text);

        foreach ($chunks as $chunk) {
            $message = $this->send(new PopCommand($collection, $bucket, $object, $chunk));
            if($message == false || $message == "") {
                throw new \InvalidArgumentException();
            }
        }
        return $message;
    }

    public function count($collection, $bucket = null, $object = null)
    {
        $message = $this->send(new CountCommand($collection, $bucket, $object));

        return $message->get('count');
    }

    private function splitString(string  $text): array
    {
        return  str_split($text, ($this->bufferSize - 30));
    }
}