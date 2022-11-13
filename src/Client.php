<?php

namespace Psonic;

use Psonic\Exceptions\ConnectionException;
use Psonic\Contracts\Client as ClientInterface;
use Psonic\Contracts\Command as CommandInterface;
use Psonic\Contracts\Response as ResponseInterface;
use TypeError;

class Client implements ClientInterface
{
    /** @var resource $resource */
    private $resource;

    /** @var string  */
    private $host;
    /** @var int */
    private $port;
    /** @var int|null  */
    private $errorNo;
    /** @var string  */
    private $errorMessage;
    /** @var int  */
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
        try {
            fwrite($this->resource, $command);
        }catch (TypeError $e) {
            throw new ConnectionException("Not connected to sonic. " . $e->getMessage());
        }

        return $this->read();
    }

    /**
     * reads the buffer from a given stream
     * @return ResponseInterface
     */
    public function read(): ResponseInterface
    {
        $string = fgets($this->resource);

        if($string === false) {
            throw new \RuntimeException("Unable to read from stream");
        }

        if(empty($string)) {
            throw new \RuntimeException("Read empty string from stream");
        }

        $message = explode("\r\n", $string)[0];

        return new SonicResponse($message);
    }

    /**
     * @throws ConnectionException
     * connects to the socket
     */
    public function connect(): void
    {
        $resource = stream_socket_client("tcp://{$this->host}:{$this->port}", $this->errorNo, $this->errorMessage, $this->maxTimeout);
        if (!$resource) {
            throw new ConnectionException("Unable to connect to sonic search engine with given host: $this->host and port: $this->port}. Error code $this->errorNo with $this->errorMessage was produced");
        }
        $this->resource = $resource;
    }

    /**
     * Disconnects from a socket
     */
    public function disconnect(): void
    {
        $result = stream_socket_shutdown($this->resource, STREAM_SHUT_WR);

        if(!$result) {
            throw new \RuntimeException("Unable to shut down stream socket connection");
        }

        fclose($this->resource);
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
