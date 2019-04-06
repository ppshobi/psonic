<?php

namespace Psonic\Commands\Search;

use Psonic\Commands\Command;

class StartSearchChannelCommand extends Command
{
    private $command    = 'START';
    private $parameters = [];

    public function __construct($password = 'SecretPassword')
    {
        $this->parameters = [
            'mode' => 'search',
            'password' => $password,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}