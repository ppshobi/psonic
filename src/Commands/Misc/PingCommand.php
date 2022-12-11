<?php


namespace Psonic\Commands\Misc;

use Psonic\Commands\Command;


final class PingCommand extends Command
{
    private string $command    = 'PING';
    /** @var array<mixed> $parameters */
    private array $parameters = [];

    /**
     * PingCommand constructor.
     */
    public function __construct()
    {
        parent::__construct($this->command, $this->parameters);
    }
}
