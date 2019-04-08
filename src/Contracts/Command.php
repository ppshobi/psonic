<?php

namespace Psonic\Contracts;


interface Command
{
    /**
     * @return string
     * ultimately a command instance get translated into a string
     */
    public function __toString(): string ;
}