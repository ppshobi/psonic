<?php

namespace Psonic\Commands\Search;

use Psonic\Commands\Command;

class QueryCommand extends Command
{
    private $command    = 'QUERY';
    private $parameters = [];

    /**
     * QueryCommand constructor.
     * @param string $collection
     * @param string $bucket
     * @param string $terms
     * @param null $limit
     */
    public function __construct(string $collection, string $bucket, string $terms, $limit = null)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
            'terms'      => quote($terms),
            'limit'      => $limit,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}