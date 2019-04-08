<?php


namespace Psonic\Commands\Misc;

use Psonic\Commands\Command;


final class QuitChannelCommand extends Command
{
    private $command = 'QUIT';
    private $parameters = [];

    /**
     * QuitChannelCommand constructor.
     */
    public function __construct()
    {
        parent::__construct($this->command, $this->parameters);
    }
}