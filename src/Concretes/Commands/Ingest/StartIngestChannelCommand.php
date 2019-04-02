<?php

namespace Psonic\Concretes\Commands\Ingest;

use Psonic\Concretes\Commands\Command;

class StartIngestChannelCommand extends Command
{
    private $command    = 'START';
    private $parameters = [];

    public function __construct($password = 'SecretPassword')
    {
        $this->parameters = [
            'mode' => 'ingest',
            'password' => $password,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}