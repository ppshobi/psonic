<?php


namespace Psonic\Commands\Misc;

use Psonic\Commands\Command;


class PingCommand extends Command
{
    private $command = 'PING';
    private $parameters = [];

    public function __construct()
    {
        parent::__construct($this->command, $this->parameters);
    }
}