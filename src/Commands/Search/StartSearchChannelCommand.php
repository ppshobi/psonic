<?php

namespace Psonic\Commands\Search;

use Psonic\Commands\Command;

final class StartSearchChannelCommand extends Command
{
    private string $command    = 'START';
    /** @var array<mixed> $parameters */
    private array $parameters = [];

    public function __construct(string $password)
    {
        $this->parameters = [
            'mode' => 'search',
            'password' => $password,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}
