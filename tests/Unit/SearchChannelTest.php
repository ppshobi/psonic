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
use Tests\TestCase;

class SearchChannelTest extends TestCase
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
     */
    public function it_can_query_sonic_protocol_and_return_matched_records()
    {
        $this->ingest->connect();
        $this->search->connect();
        $this->control->connect();

        $this->ingest->flushc($this->collection);

        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi Shobi how are you?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1235", "hi are you fine ?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "3456", "Jomit? How are you?")->getStatus());

        $this->control->consolidate();

        $this->assertTrue(is_array($this->search->query($this->collection, $this->bucket, "are")));
    }

    /**
     * @test
     *
     */
    public function it_can_query_sonic_protocol_and_return_suggestions()
    {
        $this->ingest->connect();
        $this->search->connect();
        $this->control->connect();

        $this->ingest->flushc($this->collection);

        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi Shobi how are you?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1235", "hi are you fine ?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "3456", "Jomit? How are you?")->getStatus());

        $this->control->consolidate();
        $results = $this->search->suggest($this->collection, $this->bucket, "sho");
        $this->assertTrue(is_array($results));
        $this->assertContains("shobi", $results);
    }

}