<?php 
namespace Psonic\Contracts;

interface Client
{
    public function connect();

    public function disconnect();

    public function send(Command $command);
}
