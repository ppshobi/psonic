<?php

namespace Psonic\Commands\Ingest;

use Psonic\Commands\Command;

final class CountCommand extends Command
{
    private $command    = 'COUNT';
    private $parameters = [];

    /**
     * Counts the number of objects
     * CountCommand constructor.
     * @param string $collection
     * @param string|null $bucket
     * @param string|null $object
     */
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