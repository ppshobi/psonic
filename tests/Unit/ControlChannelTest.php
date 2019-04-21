<?php
/**
 * Author: ppshobi@gmail.com
 *
 */
namespace Tests\Unit;

use Psonic\Control;
use Psonic\Client;

use Tests\TestCase;

class ControlChannelTest extends TestCase
{
    public function setUp(): void
    {
        $this->control = new Control(new Client());
    }

    /**
     * @test
     * @group connected
     */
    public function it_can_trigger_consolidation()
    {
        $this->control->connect();

        $this->assertEquals("OK", $this->control->consolidate());

    }
}