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

    /**
     * @var integer
     */
    protected $bufferSize;

    /**
     * Channel constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed|void
     * @throws ConnectionException
     */
    public function connect()
    {
        $this->client->connect();
        $response = $this->client->read();
        if($response->getStatus() == "CONNECTED") {
            return;
        }
        throw new ConnectionException;
    }

    /**
     * @return Response
     */
    public function disconnect(): Response
    {
        $message = $this->client->send(new QuitChannelCommand);
        $this->client->disconnect();
        return $message;
    }

    /**
     * @return Response
     */
    public function ping(): Response
    {
        return $this->client->send(new PingCommand);
    }

    /**
     * @return Response
     */
    public function read(): Response
    {
        return $this->client->read();
    }

    /**
     * @param Command $command
     * @return Response
     */
    public function send(Command $command): Response
    {
        return $this->client->send($command);
    }
}