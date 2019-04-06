<?php


namespace Psonic\Channels;
use Psonic\Contracts\Client;
use Psonic\Contracts\Command;
use Psonic\Contracts\Response;
use Psonic\Commands\Misc\PingCommand;
use Psonic\Exceptions\ConnectionException;
use Psonic\Commands\Misc\QuitChannelCommand;
use Psonic\Contracts\Channel as ChannelInterface;

abstract class Channel implements ChannelInterface
{
    /**
     * @var Client
     */
    protected $client;

    protected $bufferSize;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function connect()
    {
        $this->client->connect();
        $response = $this->client->read();
        if($response->getStatus() == "CONNECTED") {
            return;
        }
        throw new ConnectionException;
    }

    public function disconnect()
    {
        $message = $this->client->send(new QuitChannelCommand);
         $this->client->disconnect();
        return $message;
    }

    public function ping(): Response
    {
        return $this->client->send(new PingCommand);
    }

    public function read(): Response
    {
        return $this->client->read();
    }

    public function send(Command $command): Response
    {
        return $this->client->send($command);
    }
}