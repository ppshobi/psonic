<?php
namespace Psonic\Contracts;

use Psonic\Exceptions\ConnectionException;

interface Client
{
    /**
     * Connects to a socket
     * @throws ConnectionException
     */
    public function connect(): void;

    /**
     * disconnects the socket
     */
    public function disconnect(): void;

    /**
     * @param Command $command
     * @return Response
     */
    public function send(Command $command): Response;

    /**
     * @return Response
     * reads the output buffer
     */
    public function read(): Response;
}
