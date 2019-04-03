<?php

namespace Psonic\Concretes\Commands\Control;

use Psonic\Concretes\Commands\Command;

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