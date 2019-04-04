<?php
/**
 * Author: ppshobi@gmail.com
 *
 */
namespace Tests\Unit;

use Psonic\Concretes\Channels\Control;
use Psonic\Concretes\Channels\Ingest;
use Psonic\Concretes\Channels\Search;
use Psonic\Concretes\Client;
use Psonic\Concretes\Commands\Misc\PingCommand;
use Psonic\Contracts\Command;
use Psonic\Contracts\Response;
use Psonic\Exceptions\ConnectionException;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    public function setUp(): void
    {
        $this->search = new Search(new Client());
        $this->ingest = new Ingest(new Client());
        $this->control = new Control(new Client());
    }

    /**
     * @test
     *
     **/
    public function channels_can_connect_to_sonic_channel_protocol()
    {
        $this->assertEquals("STARTED", $this->search->connect()->getStatus());
        $this->assertEquals("STARTED", $this->ingest->connect()->getStatus());
        $this->assertEquals("STARTED", $this->control->connect()->getStatus());
    }

    /**
     * @test
     *
     **/
    public function channels_can_send_a_command_and_returns_a_response()
    {
        $this->connect_all_channels();
        $searchResponse = $this->search->send(new PingCommand);
        $ingestResponse = $this->ingest->send(new PingCommand);
        $controlResponse = $this->control->send(new PingCommand);

        $this->assertInstanceOf(Response::class, $searchResponse);
        $this->assertInstanceOf(Response::class, $ingestResponse);
        $this->assertInstanceOf(Response::class, $controlResponse);

        $this->assertEquals("PONG", $searchResponse);
        $this->assertEquals("PONG", $ingestResponse);
        $this->assertEquals("PONG", $controlResponse);
    }

    /**
     * @test
     */
    public function channels_can_disconnect_from_sonic_protocol()
    {
        $this->connect_all_channels();
        $searchResponse = $this->search->disconnect();
        $ingestResponse = $this->ingest->disconnect();
        $controlResponse = $this->control->disconnect();

        $this->assertEquals("ENDED", $searchResponse->getStatus());
        $this->assertEquals("ENDED", $ingestResponse->getStatus());
        $this->assertEquals("ENDED", $controlResponse->getStatus());
    }

    private function connect_all_channels()
    {
        $this->search->connect();
        $this->ingest->connect();
        $this->control->connect();
    }

}