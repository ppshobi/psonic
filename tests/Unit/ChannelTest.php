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


}