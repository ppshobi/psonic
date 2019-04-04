<?php
/**
 * Author: ppshobi@gmail.com
 *
 */
namespace Tests\Unit;

use Psonic\Concretes\Channels\Control;
use Psonic\Concretes\Client;

use Tests\TestCase;

class ControlChannelTest extends TestCase
{
    public function setUp(): void
    {
        $this->control = new Control(new Client());
    }

    /**
     * @test
     *
     */
    public function it_can_trigger_consolidation()
    {
        $this->control->connect();

        $this->assertEquals("OK", $this->control->consolidate());

    }
}