<?php

namespace Psonic\Commands\Search;

use Psonic\Commands\Command;

final class QueryCommand extends Command
{
    private string $command    = 'QUERY';
    /** @var array<mixed> $parameters */
    private array $parameters = [];

    public function __construct(string $collection, string $bucket, string $terms, int $limit = null, int $offset = null,string $locale = null)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
            'terms'      => self::wrapInQuotes($terms),
            'limit'      => $limit ? "LIMIT($limit)": null,
            'offset'     => $offset ? "OFFSET($offset)": null,
            'locale'     => $locale ? "LANG($locale)": null,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}
