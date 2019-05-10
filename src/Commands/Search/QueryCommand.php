<?php

namespace Psonic\Commands\Search;

use Psonic\Commands\Command;

final class QueryCommand extends Command
{
    private $command    = 'QUERY';
    private $parameters = [];

    /**
     * QueryCommand constructor.
     * @param string $collection
     * @param string $bucket
     * @param string $terms
     * @param null $limit
     * @param null $locale
     */
    public function __construct(string $collection, string $bucket, string $terms, $limit = null, $locale = null)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
            'terms'      => quote($terms),
            'limit'      => $limit ? "LIMIT($limit)": null,
            'locale'      => $locale ? "LANG($locale)": null,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}