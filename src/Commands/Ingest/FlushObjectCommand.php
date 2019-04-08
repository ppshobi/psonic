<?php

namespace Psonic\Commands\Ingest;

use Psonic\Commands\Command;

final class FlushObjectCommand extends Command
{
    private $command    = 'FLUSHO';
    private $parameters = [];

    /**
     * Flushes the text from an object
     * FlushObjectCommand constructor.
     * @param string $collection
     * @param string $bucket
     * @param string $object
     */
    public function __construct(string $collection, string $bucket, string $object)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
            'object'     => $object,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}