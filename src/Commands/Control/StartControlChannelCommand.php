<?php

namespace Psonic\Commands\Control;

use Psonic\Commands\Command;

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