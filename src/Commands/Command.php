<?php

namespace Psonic\Commands;

use Psonic\Contracts\Command as CommandInterface;

abstract class Command implements CommandInterface
{
    private string $command;
    /**
     * @var array<mixed>
     */
    private array $parameters;

    /**
     * @param array<mixed> $parameters
     */
    public function __construct(string $command, array $parameters = [])
    {
        $this->command = $command;
        $this->parameters = $parameters;
    }

    /**
     * Wrap the string in quotes, and normalize whitespace. Also remove double quotes.
     */
    protected function wrapInQuotes(string $string):string
    {
        /** @var string $string */
        $string = preg_replace('/[\r\n\t"]/', ' ', $string);
        $string = '"' . str_replace('"', '\"', $string) . '"';
        return $string;
    }

    public function __toString(): string
    {
        return $this->command . " " . implode(" ", $this->parameters) . "\n";
    }
}
