<?php

namespace Psonic\Commands\Search;

use Psonic\Commands\Command;

final class StartSearchChannelCommand extends Command
{
    private $command    = 'START';
    private $parameters = [];

    /**
     * StartSearchChannelCommand constructor.
     * @param string $password
     */
    public function __construct($password)
    {
        $this->parameters = [
            'mode' => 'search',
            'password' => $password,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}