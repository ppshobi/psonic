<?php

namespace Psonic\Concretes\Commands\Control;

use Psonic\Concretes\Commands\Command;

class StartControlChannelCommand extends Command
{
    private $command    = 'START';
    private $parameters = [];

    public function __construct($password = 'SecretPassword')
    {
        $this->parameters = [
            'mode' => 'control',
            'password' => $password,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}