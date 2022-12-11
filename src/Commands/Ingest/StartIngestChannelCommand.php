<?php

namespace Psonic\Commands\Ingest;

use Psonic\Commands\Command;

final class StartIngestChannelCommand extends Command
{
    private string $command    = 'START';
    /** @var array<mixed> $parameters */
    private array $parameters = [];

    /**
     * StartIngestChannelCommand constructor.
     * @param string $password
     */
    public function __construct($password)
    {
        $this->parameters = [
            'mode' => 'ingest',
            'password' => $password,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}
