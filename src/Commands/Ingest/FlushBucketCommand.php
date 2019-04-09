<?php

namespace Psonic\Commands\Ingest;

use Psonic\Commands\Command;

final class FlushBucketCommand extends Command
{
    private $command    = 'FLUSHB';
    private $parameters = [];

    /**
     * Flushes a given bucket in a collection
     * FlushBucketCommand constructor.
     * @param string $collection
     * @param string $bucket
     */
    public function __construct(string $collection, string $bucket)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}