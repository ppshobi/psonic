<?php

namespace Psonic\Commands\Control;

use Psonic\Commands\Command;

final class InfoCommand extends Command
{
    private $command    = 'INFO';
    private $parameters = [];

    /**
     * Info Command constructor.
     */
    public function __construct()
    {
        parent::__construct($this->command, $this->parameters);
    }
}