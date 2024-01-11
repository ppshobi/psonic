<?php

namespace Psonic\Commands\Ingest;

use Psonic\Commands\Command;

final class FlushCollectionCommand extends Command
{
    private string $command    = 'FLUSHC';
    /** @var array<mixed> $parameters */
    private array $parameters = [];

    /**
     * Flushes a given collection
     * FlushCollectionCommand constructor.
     * @param string $collection
     */
    public function __construct(string $collection)
    {
        $this->parameters = [
            'collection' => $collection,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}
