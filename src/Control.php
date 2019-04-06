<?php

namespace Psonic;


use Psonic\Channels\Channel;
use Psonic\Commands\Control\StartControlChannelCommand;
use Psonic\Commands\Control\TriggerCommand;
use Psonic\Contracts\Client;

class Control extends Channel
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function connect()
    {
        parent::connect();

        $response = $this->send(new StartControlChannelCommand());

        if($bufferSize = $response->get('bufferSize')){
            $this->bufferSize = (int) $bufferSize;
        }

        return $response;
    }

    public function trigger($action)
    {
        return $this->send(new TriggerCommand($action));
    }

    public function consolidate()
    {
        return $this->trigger('consolidate');
    }
}