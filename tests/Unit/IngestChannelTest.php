<?php
/**
 * Author: ppshobi@gmail.com
 *
 */
namespace Tests\Unit;

use Psonic\Concretes\Channels\Control;
use Psonic\Concretes\Channels\Ingest;
use Psonic\Concretes\Client;
use Tests\TestCase;

class IngestChannelTest extends TestCase
{
    public function setUp(): void
    {
        $this->ingest = new Ingest(new Client());
        $this->control = new Control(new Client());
    }

    /**
     * @test
     *
     **/
    public function it_can_push_items_to_the_index()
    {
        $this->ingest->connect();
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234","hi Shobi how are you")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "4567","hi Naveen")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "7890","hi Jomit")->getStatus());
    }

    /**
     * @test
     *
     **/
    public function it_can_push_huge_items_to_the_index()
    {
        $this->ingest->connect();
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", bin2hex(openssl_random_pseudo_bytes(30000)))->getStatus());
    }

    /**
     * @test
     *
     **/
    public function it_can_pop_items_from_index()
    {
        $this->ingest->connect();
        $this->control->connect();

        $this->ingest->flushc($this->collection);
        $this->assertEquals(0, $this->ingest->count($this->collection, $this->bucket));
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi Shobi how are you")->getStatus());
        $this->control->consolidate();

        $this->assertEquals(1, $this->ingest->count($this->collection, $this->bucket));

        $this->assertEquals(1, $this->ingest->pop($this->collection, $this->bucket, "1234", "hi Shobi how are you"));
        $this->control->consolidate();
        $this->assertEquals(0, $this->ingest->count($this->collection, $this->bucket, "1234"));
    }

    /**
     * @test
     *
     **/
    public function it_can_count_the_objects()
    {
        $this->ingest->connect();
        $this->control->connect();

        $this->ingest->flushc($this->collection);
        $this->control->consolidate();

        $this->assertEquals(0, $this->ingest->count($this->collection));
        $this->assertEquals(0, $this->ingest->count($this->collection, $this->bucket));

        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi Shobi how are you?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1235", "hi Naveen how are you?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "3456", "Hi Jomit How are you?")->getStatus());

        $this->control->consolidate();

        $this->assertEquals(3, $this->ingest->count($this->collection, $this->bucket));
    }

    //TODO: separate tests for flushc, flushb, flusho commands

}