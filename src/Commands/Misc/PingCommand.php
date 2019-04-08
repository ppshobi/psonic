<?php


namespace Psonic\Commands\Misc;

use Psonic\Commands\Command;


final class PingCommand extends Command
{
    private $command = 'PING';
    private $parameters = [];

    /**
     * PingCommand constructor.
     */
    public function __construct()
    {
        parent::__construct($this->command, $this->parameters);
    }
}