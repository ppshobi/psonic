<?php

namespace Psonic\Commands\Ingest;

use Psonic\Commands\Command;

class PushCommand extends Command
{
    private $command    = 'PUSH';
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