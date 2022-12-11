<?php

namespace Psonic\Commands\Search;

use Psonic\Commands\Command;

final class SuggestCommand extends Command
{
    private string $command    = 'SUGGEST';
    /** @var array<mixed> $parameters */
    private array $parameters = [];

    /**
     * SuggestCommand constructor.
     */
    public function __construct(string $collection, string $bucket, string $terms,int $limit = null)
    {
        $this->parameters = [
            'collection' => $collection,
            'bucket'     => $bucket,
            'terms'      => self::wrapInQuotes($terms),
            'limit'      => $limit ? "LIMIT($limit)" : null,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}
