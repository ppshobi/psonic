<?php
/**
 * Author: ppshobi@gmail.com
 *
 */
namespace Tests\Unit;

use Psonic\Control;
use Psonic\Ingest;
use Psonic\Search;
use Psonic\Client;
use Psonic\Commands\Misc\PingCommand;
use Psonic\Contracts\Response;
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
     * @group connected
     **/
    public function channels_can_connect_to_sonic_channel_protocol()
    {
        $this->assertEquals("STARTED", $this->search->connect($this->password)->getStatus());
        $this->assertEquals("STARTED", $this->ingest->connect($this->password)->getStatus());
        $this->assertEquals("STARTED", $this->control->connect($this->password)->getStatus());
    }

    /**
     * @test
     * @group connected
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
     * @group connected
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
        $this->search->connect('SecretPassword1');
        $this->ingest->connect('SecretPassword1');
        $this->control->connect('SecretPassword1');
    }

}