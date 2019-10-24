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
     * @group connected
     */
    public function it_can_query_sonic_protocol_and_return_matched_records()
    {
        $this->ingest->connect($this->password);
        $this->search->connect($this->password);
        $this->control->connect($this->password);

        $this->ingest->flushc($this->collection);

        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi Shobi how are you?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1235", "hi are you fine ?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "3456", "Jomit? How are you?")->getStatus());

        $this->control->consolidate();

        $this->assertTrue(is_array($this->search->query($this->collection, $this->bucket, "are")));
    }

    /**
     * @test
     * @group connected
     */
    public function it_can_apply_limit_on_returned_records_for_a_query()
    {
        $this->ingest->connect($this->password);
        $this->search->connect($this->password);
        $this->control->connect($this->password);

        $this->ingest->flushc($this->collection);

        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi Shobi how are you?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1235", "hi are you fine ?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "3456", "Jomit? How are you?")->getStatus());

        $this->control->consolidate();

        $this->assertCount(2, $this->search->query($this->collection, $this->bucket, "are", 2));
        $this->assertCount(1, $this->search->query($this->collection, $this->bucket, "are", 1));
    }

    /**
     * @test
     * @group connected
     */
    public function it_can_apply_limit_and_offset_on_returned_records_for_a_query()
    {
        $this->ingest->connect($this->password);
        $this->search->connect($this->password);
        $this->control->connect($this->password);

        $this->ingest->flushc($this->collection);

        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi how are you")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1235", "hi are you fine ?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "3456", "Jomit? How are you?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "4567", "Annie, are you ok?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "5678", "So Annie are you ok")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "6789", "are you ok, Annie")->getStatus());

        $this->control->consolidate();

        $responses = $this->search->query($this->collection, $this->bucket, "are you", 10);
        $this->assertCount(6, $responses);

        $responses = $this->search->query($this->collection, $this->bucket, "are", 2, 3);
        $this->assertCount(2, $responses);
    }

    /**
     * @test
     * @group connected
     */
    public function it_can_apply_limit_on_returned_records_for_a_suggest_command()
    {
        $this->ingest->connect($this->password);
        $this->search->connect($this->password);
        $this->control->connect($this->password);

        $this->ingest->flushc($this->collection);

        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "coincident")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1235", "coincidental")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "3456", "coinciding")->getStatus());

        $this->control->consolidate();

        $this->assertCount(3, $this->search->suggest($this->collection, $this->bucket, "coin"));
        $this->assertCount(2, $this->search->suggest($this->collection, $this->bucket, "coin", 2));
        $this->assertCount(1, $this->search->suggest($this->collection, $this->bucket, "coin", 1));
    }

    /**
     * @test
     * @group connected
     */
    public function it_can_query_sonic_protocol_and_return_suggestions()
    {
        $this->ingest->connect($this->password);
        $this->search->connect($this->password);
        $this->control->connect($this->password);

        $this->ingest->flushc($this->collection);

        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi Shobi how are you?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1235", "hi are you fine ?")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "3456", "Jomit? How are you?")->getStatus());

        $this->control->consolidate();
        $results = $this->search->suggest($this->collection, $this->bucket, "sho");
        $this->assertTrue(is_array($results));
        $this->assertContains("shobi", $results);
    }

    // @todo
    /**
     * Implement tests for locale based query and suggest commands
     */
}
