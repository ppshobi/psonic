<?php
/**
 * Author: ppshobi@gmail.com
 *
 */
namespace Tests\Unit;

use Psonic\Concretes\Channels\Ingest;
use Psonic\Concretes\Client;
use Tests\TestCase;

class IngestChannelTest extends TestCase
{
    public function setUp(): void
    {
        $this->ingest = new Ingest(new Client());
    }

    /**
     * @test
     *
     **/
    public function it_can_push_items_to_the_index()
    {
        $this->ingest->connect();
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "messages:1234","hi Shobi how are you")->getStatus());
//        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "messages:4567","hi Naveen")->getStatus());
//        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "messages:7890","hi Jomit")->getStatus());
    }
    
}