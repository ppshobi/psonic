<?php

namespace Psonic\Commands\Ingest;

use Psonic\Commands\Command;

class StartIngestChannelCommand extends Command
{
    private $command    = 'START';
    private $parameters = [];

    /**
     * StartIngestChannelCommand constructor.
     * @param string $password
     */
    public function __construct($password = 'SecretPassword')
    {
        $this->parameters = [
            'mode' => 'ingest',
            'password' => $password,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}