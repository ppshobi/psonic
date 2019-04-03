<?php

namespace Psonic\Concretes\Commands\Ingest;

use Psonic\Concretes\Commands\Command;

class PopCommand extends Command
{
    private $command    = 'POP';
    private $parameters = [];

    public function __construct(string $collection, string $bucket, string $object, string $text)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
            'object'     => $object,
            'text'       => $this->quote($text),
        ];

        parent::__construct($this->command, $this->parameters);
    }

    private function quote(string $string)
    {
        return "\"" . $string ."\"";
    }
}