<?php


namespace Psonic\Concretes\Commands\Misc;

use Psonic\Concretes\Commands\Command;


class PingCommand extends Command
{
    private $command = 'PING';
    private $parameters = [];

    public function __construct()
    {
        parent::__construct($this->command, $this->parameters);
    }
}