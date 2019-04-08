<?php

namespace Psonic\Commands\Control;

use Psonic\Commands\Command;

final class StartControlChannelCommand extends Command
{
    private $command    = 'START';
    private $parameters = [];

    /**
     * StartControlChannelCommand constructor.
     * @param string $password
     */
    public function __construct($password = 'SecretPassword')
    {
        $this->parameters = [
            'mode' => 'control',
            'password' => $password,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}