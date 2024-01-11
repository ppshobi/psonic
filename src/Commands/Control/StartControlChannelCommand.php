<?php

namespace Psonic\Commands\Control;

use Psonic\Commands\Command;

final class StartControlChannelCommand extends Command
{
    private string $command    = 'START';
    /** @var array<mixed> $parameters */
    private array $parameters = [];

    /**
     * StartControlChannelCommand constructor.
     * @param string $password
     */
    public function __construct($password)
    {
        $this->parameters = [
            'mode' => 'control',
            'password' => $password,
        ];

        parent::__construct($this->command, $this->parameters);
    }
}
