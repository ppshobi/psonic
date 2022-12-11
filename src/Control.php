<?php

namespace Psonic;


use Psonic\Channels\Channel;
use Psonic\Contracts\Client;
use Psonic\Commands\Control\InfoCommand;
use Psonic\Commands\Control\TriggerCommand;
use Psonic\Commands\Control\StartControlChannelCommand;
use Psonic\Contracts\Response;

class Control extends Channel
{
    /**
     * Control constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function connect(string $password = 'SecretPassword'): Response
    {
        parent::connect();

        $response = $this->send(new StartControlChannelCommand($password));

        /** @var string $bufferSize */
        $bufferSize = $response->get('bufferSize');
        if ($bufferSize) {
            $this->bufferSize = (int)$bufferSize;
        }

        return $response;
    }

    public function trigger(string $action): Response
    {
        return $this->send(new TriggerCommand($action));
    }

    public function consolidate(): Response
    {
        return $this->trigger('consolidate');
    }

    public function info(): Response
    {
        return $this->send(new InfoCommand);
    }
}
