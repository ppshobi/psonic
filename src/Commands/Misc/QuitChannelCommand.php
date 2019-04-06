<?php


namespace Psonic\Commands\Misc;

use Psonic\Commands\Command;


class QuitChannelCommand extends Command
{
    private $command = 'QUIT';
    private $parameters = [];

    public function __construct()
    {
        parent::__construct($this->command, $this->parameters);
    }
}