<?php

namespace Psonic\Commands\Control;

use Psonic\Commands\Command;

final class TriggerCommand extends Command
{
    private $command    = 'TRIGGER';
    private $parameters = [];

    /**
     * TriggerCommand constructor.
     * @param string $action
     */
    public function __construct(string $action)
    {
        $this->parameters = [
            'action' => $action,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}