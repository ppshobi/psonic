<?php
/**
 * Author: ppshobi@gmail.com
 *
 */
namespace Tests\Unit;

use Psonic\Control;
use Psonic\Ingest;
use Psonic\Client;
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
     * @group connected
     **/
    public function it_can_push_items_to_the_index()
    {
        $this->ingest->connect($this->password);
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi Shobi how are you")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "4567", "hi Naveen")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "7890", "hi Jomit")->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1122", 'hi "quoted" text')->getStatus());
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1133", "Newlines\nshould\nbe\nnormalized\nto\nspaces")->getStatus());
    }

    /**
     * @test
     * @group connected
     **/
    public function it_can_push_huge_items_to_the_index()
    {
        $this->ingest->connect($this->password);

        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", bin2hex(openssl_random_pseudo_bytes(30000)))->getStatus());
    }

    /**
     * @test
     * @group connected
     **/
    public function it_can_pop_items_from_index()
    {
        $this->ingest->connect($this->password);
        $this->control->connect($this->password);

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
     * @group connected
     **/
    public function it_can_count_the_objects()
    {
        $this->ingest->connect($this->password);
        $this->control->connect($this->password);

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

    /**
     * @test
     * @group connected
     **/
    public function it_can_flush_a_collection()
    {
        $this->ingest->connect($this->password);
        $this->ingest->flushc($this->collection);
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi Shobi how are you?")->getStatus());
        $this->assertEquals(1, $this->ingest->flushc($this->collection));
    }

    /**
     * @test
     * @group connected
     **/
    public function it_can_flush_a_bucket()
    {
        $this->ingest->connect($this->password);
        $this->ingest->flushc($this->collection);
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "123", "hi Shobi how are you?")->getStatus());
        $this->assertEquals(1, $this->ingest->flushb($this->collection, $this->bucket));
    }

    /**
     * @test
     * @group connected
     **/
    public function it_can_flush_an_object()
    {
        $this->ingest->connect($this->password);
        $this->ingest->flushc($this->collection);
        $this->assertEquals("OK", $this->ingest->push($this->collection, $this->bucket, "1234", "hi Shobi how are you?")->getStatus());
        $this->assertEquals(1, $this->ingest->flusho($this->collection, $this->bucket, "1234"));
    }

    // @todo
    /**
     * Implement tests for locale based ingestion
     */
}
