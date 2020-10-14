<?php

namespace Psonic\Commands\Ingest;

use Psonic\Commands\Command;

final class PopCommand extends Command
{
    private $command    = 'POP';
    private $parameters = [];

    /**
     * pops a text from a given object
     * PopCommand constructor.
     * @param string $collection
     * @param string $bucket
     * @param string $object
     * @param string $text
     */
    public function __construct(string $collection, string $bucket, string $object, string $text)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
            'object'     => $object,
            'text'       => self::wrapInQuotes($text),
        ];

        parent::__construct($this->command, $this->parameters);
    }
}