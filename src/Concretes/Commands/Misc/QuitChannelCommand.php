<?php


namespace Psonic\Concretes\Commands\Misc;

use Psonic\Concretes\Commands\Command;


class QuitChannelCommand extends Command
{
    private $command = 'QUIT';
    private $parameters = [];

    public function __construct()
    {
        parent::__construct($this->command, $this->parameters);
    }
}