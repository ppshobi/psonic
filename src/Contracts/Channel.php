<?php

namespace Psonic\Contracts;


interface Channel
{
    public function connect();
    public function disconnect();
}