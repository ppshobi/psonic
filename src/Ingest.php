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

    /**
     * @return mixed|Contracts\Response|void
     * @throws Exceptions\ConnectionException
     */
    public function connect($password = 'SecretPassword')
    {
        parent::connect();

        $response = $this->send(new StartIngestChannelCommand($password));

        if ($bufferSize = $response->get('bufferSize')) {
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

    /**
     * @param $collection
     * @param null $bucket
     * @param null $object
     * @return mixed
     */
    public function count($collection, $bucket = null, $object = null)
    {
        $message = $this->send(new CountCommand($collection, $bucket, $object));

        return $message->get('count');
    }

    /**
     * @param $collection
     * @return mixed
     */
    public function flushc($collection)
    {
        $message = $this->send(new FlushCollectionCommand($collection));
        return $message->getCount();
    }

    /**
     * @param $collection
     * @param $bucket
     * @return integer
     */
    public function flushb($collection, $bucket)
    {
        $message = $this->send(new FlushBucketCommand($collection, $bucket));
        return $message->getCount();
    }

    /**
     * @param $collection
     * @param $bucket
     * @param $object
     * @return mixed
     */
    public function flusho($collection, $bucket, $object)
    {
        $message = $this->send(new FlushObjectCommand($collection, $bucket, $object));
        return $message->getCount();
    }

    /**
     * @param string $collection
     * @param string $bucket
     * @param string $key
     * @param string $text
     * @return array
     */
    private function splitString(string $collection, string $bucket, string $key, string  $text): array
    {
        return str_split($text, ($this->bufferSize - (strlen($key . $collection . $bucket) + 20)));
    }
}
