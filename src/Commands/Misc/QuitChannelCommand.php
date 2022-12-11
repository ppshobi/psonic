<?php


namespace Psonic\Commands\Misc;

use Psonic\Commands\Command;


final class QuitChannelCommand extends Command
{
    private string $command    = 'QUIT';
    /** @var array<mixed> $parameters */
    private array $parameters = [];

    /**
     * QuitChannelCommand constructor.
     */
    public function __construct()
    {
        parent::__construct($this->command, $this->parameters);
    }
}
