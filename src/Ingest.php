<?php

namespace Psonic;

use Psonic\Channels\Channel;
use Psonic\Commands\Command;
use Psonic\Commands\Ingest\CountCommand;
use Psonic\Commands\Ingest\FlushBucketCommand;
use Psonic\Commands\Ingest\FlushCollectionCommand;
use Psonic\Commands\Ingest\FlushObjectCommand;
use Psonic\Commands\Ingest\PopCommand;
use Psonic\Commands\Ingest\PushCommand;
use Psonic\Commands\Ingest\StartIngestChannelCommand;
use Psonic\Contracts\Client;
use Psonic\Contracts\Response;
use InvalidArgumentException;

class Ingest extends Channel
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function connect()
    {
        parent::connect();

        $response = $this->send(new StartIngestChannelCommand);

        if($bufferSize = $response->get('bufferSize')){
            $this->bufferSize = (int) $bufferSize;
        }

        return $response;
    }

    public function push(string $collection, string $bucket, string $object, string $text)
    {

        $chunks = $this->splitString($collection,$bucket, $object, $text);

        if($text == "" || empty($chunks)) {
            throw new InvalidArgumentException("The parameter \$text is empty");
        }
        foreach ($chunks as $chunk) {
            $message = $this->send(new PushCommand($collection, $bucket, $object, $chunk));
            if($message == false || $message == "") {
                throw new InvalidArgumentException();
            }
        }
        return $message;
    }

    public function pop(string $collection, string $bucket, string $object, string $text)
    {
        $chunks = $this->splitString($collection,$bucket, $object, $text);

        foreach ($chunks as $chunk) {
            $message = $this->send(new PopCommand($collection, $bucket, $object, $chunk));
            if($message == false || $message == "") {
                throw new InvalidArgumentException();
            }
        }

        return $message->get('count');
    }

    public function count($collection, $bucket = null, $object = null)
    {
        $message = $this->send(new CountCommand($collection, $bucket, $object));

        return $message->get('count');
    }

    public function flushc($collection)
    {
        $message = $this->send(new FlushCollectionCommand($collection));
        return $message->getCount();
    }

    public function flushb($collection,$bucket)
    {
        $message = $this->send(new FlushBucketCommand($collection, $bucket));
        return $message->getCount();
    }

    public function flusho($collection, $bucket, $object)
    {
        $message = $this->send(new FlushObjectCommand($collection, $bucket, $object));
        return $message->getCount();
    }

    private function splitString(string $collection, string $bucket, string $key, string  $text): array
    {
        return str_split($text, ($this->bufferSize - (strlen($key . $collection . $bucket) + 9)));
    }
}