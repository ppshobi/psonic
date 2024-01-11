<?php

namespace Psonic;

use Psonic\Channels\Channel;
use Psonic\Contracts\Client;
use InvalidArgumentException;
use Psonic\Commands\Ingest\PopCommand;
use Psonic\Commands\Ingest\PushCommand;
use Psonic\Commands\Ingest\CountCommand;
use Psonic\Commands\Ingest\FlushObjectCommand;
use Psonic\Commands\Ingest\FlushBucketCommand;
use Psonic\Commands\Ingest\FlushCollectionCommand;
use Psonic\Commands\Ingest\StartIngestChannelCommand;
use Psonic\Contracts\Response;

class Ingest extends Channel
{
    /**
     * Ingest constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function connect(string $password = 'SecretPassword'): Response
    {
        parent::connect();

        $response = $this->send(new StartIngestChannelCommand($password));

        /** @var string $bufferSize */
        $bufferSize = $response->get('bufferSize');
        if ($bufferSize) {
            $this->bufferSize = (int)$bufferSize;
        }

        return $response;
    }

    /**
     * @param string $collection
     * @param string $bucket
     * @param string $object
     * @param string $text
     * @param string $locale
     * @return Contracts\Response
     */
    public function push(string $collection, string $bucket, string $object, string $text, $locale = null)
    {

        $chunks = $this->splitString($collection, $bucket, $object, $text);

        if ($text == "" || empty($chunks)) {
            throw new InvalidArgumentException("The parameter \$text is empty");
        }
        foreach ($chunks as $chunk) {
            $message = $this->send(new PushCommand($collection, $bucket, $object, $chunk, $locale));
            if ($message == false || $message == "") {
                throw new InvalidArgumentException();
            }
        }
        return $message;
    }

    /**
     * @param string $collection
     * @param string $bucket
     * @param string $object
     * @param string $text
     * @return mixed
     */
    public function pop(string $collection, string $bucket, string $object, string $text)
    {
        $chunks = $this->splitString($collection, $bucket, $object, $text);
        $count  = 0;
        foreach ($chunks as $chunk) {
            $message = $this->send(new PopCommand($collection, $bucket, $object, $chunk));
            if ($message == false || $message == "") {
                throw new InvalidArgumentException();
            }
            $count += $message->get('count');
        }

        return $count;
    }

    public function count(string $collection,string $bucket = null, string $object = null): int
    {
        /** @var SonicResponse $message */
        $message = $this->send(new CountCommand($collection, $bucket, $object));

        /** @var string $count */
        $count = $message->get('count');
        return (int)$count;
    }


    public function flushc(string $collection): int
    {
        /** @var SonicResponse $message */
        $message = $this->send(new FlushCollectionCommand( $collection));

        return $message->getCount();
    }


    public function flushb(string $collection, string $bucket): int
    {
        /** @var SonicResponse $message */
        $message = $this->send(new FlushBucketCommand($collection, $bucket));
        return $message->getCount();
    }

    public function flusho(string $collection,string $bucket,string $object): int
    {
        /** @var SonicResponse $message */
        $message = $this->send(new FlushObjectCommand($collection, $bucket, $object));
        return $message->getCount();
    }

    /**
     * @return array<string>
     */
    private function splitString(string $collection, string $bucket, string $key, string  $text): array
    {
        $extraBytesRequired = strlen($key . $collection . $bucket) + 20;
        $splitLength = $this->bufferSize - $extraBytesRequired;
        if($splitLength<=0) {
            throw new \RuntimeException("Insufficient buffer size for splitting the message string Given "
                . $splitLength
                . ". Buffersize should be more than {$extraBytesRequired} to accomodate the collection, bucket and key name(s) length in the message");
        }
        return str_split($text, $splitLength);
    }
}
