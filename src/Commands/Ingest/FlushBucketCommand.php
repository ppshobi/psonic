<?php

namespace Psonic\Commands\Ingest;

use Psonic\Commands\Command;

class FlushBucketCommand extends Command
{
    private $command    = 'FLUSHO';
    private $parameters = [];

    public function __construct(string $collection, string $bucket)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}