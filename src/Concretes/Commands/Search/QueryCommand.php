<?php

namespace Psonic\Concretes\Commands\Search;

use Psonic\Concretes\Commands\Command;

class QueryCommand extends Command
{
    private $command    = 'QUERY';
    private $parameters = [];

    public function __construct(string $collection, string $bucket, string $terms, $limit = null)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
            'terms'      => $this->quote($terms),
            'limit'      => $limit,
        ];

        parent::__construct($this->command, $this->parameters);
    }

    private function quote(string $string)
    {
        return "\"" . $string ."\"";
    }
}