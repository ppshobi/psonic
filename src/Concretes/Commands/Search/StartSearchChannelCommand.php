<?php

namespace Psonic\Concretes\Commands\Search;

use Psonic\Concretes\Commands\Command;

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