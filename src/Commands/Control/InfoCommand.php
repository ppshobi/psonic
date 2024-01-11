<?php

namespace Psonic\Commands\Control;

use Psonic\Commands\Command;

final class InfoCommand extends Command
{
    private string $command    = 'INFO';
    /** @var array<mixed> $parameters */
    private array $parameters = [];

    /**
     * Info Command constructor.
     */
    public function __construct()
    {
        parent::__construct($this->command, $this->parameters);
    }
}
