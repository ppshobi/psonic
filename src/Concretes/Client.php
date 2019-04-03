<?php

namespace Psonic\Concretes;

use Psonic\Concretes\Commands\Misc\QuitChannelCommand;
use Psonic\Contracts\Client as ClientInterface;
use Psonic\Contracts\Response as ResponseInterface;
use Psonic\Contracts\Command as CommandInterface;
use Psonic\Exceptions\ConnectionException;

class Client implements ClientInterface 
{
    private $resource;

    private $host;
    private $errorNo;
    private $errorMessage;
    private $maxTimeout;

    public function __construct($host = 'localhost', $port = 1491, $timeout = 30)
    {
        $this->host = $host;
        $this->port = $port;
        $this->maxTimeout = $timeout;
        $this->errorNo = null;
        $this->errorMessage = '';
    }

    public function send(CommandInterface $command): ResponseInterface
    {
        if(! $this->resource ) {
            throw new ConnectionException();
        }

        fwrite($this->resource, $command);
        $message = stream_get_line($this->resource, 2048, "\r\n");
        return new SonicResponse($message);
    }

    public function connect()
    {
        if(! $this->resource = stream_socket_client("tcp://{$this->host}:{$this->port}", $this->errno, $this->errstr, $this->maxTimeout)) {
            throw new ConnectionException();
        }

        $message = stream_get_line($this->resource, 2048, "\r\n");
        return new SonicResponse($message);
//        return $this;
    }

    public function disconnect()
    {
        $this->send(new QuitChannelCommand);
        stream_socket_shutdown($this->resource, STREAM_SHUT_WR);
        $this->resource = null;
    }

    public function clearBuffer()
    {
        stream_get_line($this->resource, 4096, "\r\n");
    }
}
