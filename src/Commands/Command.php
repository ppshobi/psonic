<?php

namespace Psonic\Commands;

use Psonic\Contracts\Command as CommandInterface;

abstract class Command implements CommandInterface
{
    private $command;
    private $parameters;

    public function __construct($command, $parameters = [])
    {
        $this->command = $command;
        $this->parameters = $parameters;
    }

    /**
     * Wrap the string in quotes, and escape any double quotes that are already in the string
     */
    protected function wrapInQuotes($string)
    {
        return '"' . str_replace('"', '\"', $string) . '"';
    }

    public function __toString(): string
    {
        return $this->command . " " . implode(" ", $this->parameters) . "\n";
    }
}
