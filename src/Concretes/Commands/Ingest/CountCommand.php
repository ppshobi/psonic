<?php

namespace Psonic\Concretes\Commands\Ingest;

use Psonic\Concretes\Commands\Command;

class CountCommand extends Command
{
    private $command    = 'COUNT';
    private $parameters = [];

    public function __construct(string $collection, string $bucket = null, string $object = null)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
            'object'     => $object,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}