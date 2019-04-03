<?php

namespace Psonic\Concretes\Commands\Ingest;

use Psonic\Concretes\Commands\Command;

class FlushCollectionCommand extends Command
{
    private $command    = 'FLUSHC';
    private $parameters = [];

    public function __construct(string $collection)
    {
        $this->parameters = [
            'collection' => $collection,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}