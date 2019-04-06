<?php

namespace Psonic\Commands\Control;

use Psonic\Commands\Command;

class TriggerCommand extends Command
{
    private $command    = 'TRIGGER';
    private $parameters = [];

    public function __construct(string $action)
    {
        $this->parameters = [
            'action' => $action,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}