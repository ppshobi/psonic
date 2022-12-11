<?php

namespace Psonic\Commands\Control;

use Psonic\Commands\Command;

final class TriggerCommand extends Command
{
    private string $command    = 'TRIGGER';
    /** @var array<mixed> $parameters */
    private array $parameters = [];

    /**
     * TriggerCommand constructor.
     */
    public function __construct(string $action)
    {
        $this->parameters = [
            'action' => $action,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}
