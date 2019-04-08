<?php

namespace Psonic;

use Psonic\Exceptions\ConnectionException;
use Psonic\Contracts\Client as ClientInterface;
use Psonic\Contracts\Command as CommandInterface;
use Psonic\Contracts\Response as ResponseInterface;

class Client implements ClientInterface
{
    private $resource;

    private $host;
    private $port;
    private $errorNo;
    private $errorMessage;
    private $maxTimeout;

    /**
     * Client constructor.
     * @param string $host
     * @param int $port
     * @param int $timeout
     */
    public function __construct($host = 'localhost', $port = 1491, $timeout = 30)
    {
        $this->host = $host;
        $this->port = $port;
        $this->maxTimeout = $timeout;
        $this->errorNo = null;
        $this->errorMessage = '';
    }

    /**
     * @param CommandInterface $command
     * @return ResponseInterface
     * @throws ConnectionException
     */
    public function send(CommandInterface $command): ResponseInterface
    {
        if (!$this->resource) {
            throw new ConnectionException();
        }

        fwrite($this->resource, $command);
        return $this->read();
    }

    /**
     * reads the buffer from a given stream
     * @return ResponseInterface
     */
    public function read(): ResponseInterface
    {
        $message = stream_get_line($this->resource, 2048, "\r\n");
        return new SonicResponse($message);
    }

    /**
     * @throws ConnectionException
     * connects to the socket
     */
    public function connect()
    {
        if (!$this->resource = stream_socket_client("tcp://{$this->host}:{$this->port}", $this->errorNo, $this->errorMessage, $this->maxTimeout)) {
            throw new ConnectionException();
        }
    }

    /**
     * Disconnects from a socket
     */
    public function disconnect()
    {
        stream_socket_shutdown($this->resource, STREAM_SHUT_WR);
        $this->resource = null;
    }

    /**
     * @return bool
     * clears the output buffer
     */
    public function clearBuffer()
    {
        stream_get_line($this->resource, 4096, "\r\n");
        return true;
    }
}
