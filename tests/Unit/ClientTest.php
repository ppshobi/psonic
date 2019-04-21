<?php
/**
 * Author: ppshobi@gmail.com
 *
 */
namespace Tests\Unit;

use Psonic\Client;
use Psonic\Commands\Misc\PingCommand;
use Psonic\Contracts\Command;
use Psonic\Contracts\Response;
use Psonic\Exceptions\ConnectionException;
use Tests\TestCase;

class ClientTest extends TestCase
{
    public function setUp(): void
    {
        $this->client = new Client();
    }

    /**
     * @test
     * @group connected
     */
    public function the_client_can_connect_to_socket()
    {
        $this->client->connect();
        $this->assertEquals("CONNECTED", $this->client->read()->getStatus());
    }

    /**
     * @test
     * @group connected
     */
    public function the_client_can_disconnect_from_socket_and_throws_exception_if_connection_is_not_present()
    {
        $this->client->connect();
        $this->client->disconnect();
        $this->expectException(ConnectionException::class);
        $this->client->send(new PingCommand);
    }

    /**
     * @test
     * @group connected
     */
    public function the_client_can_send_command_and_read_its_response()
    {
        $this->client->connect();
        $response = $this->client->read();

        $this->assertEquals("CONNECTED", $response->getStatus());
    }

    /**
     * @test
     * @group connected
     */
    public function the_client_can_clear_the_output_buffer()
    {
        $this->client->connect();
        $this->assertTrue($this->client->clearBuffer());
    }


    /**
     * @test
     * @group connected
     */
    public function the_client_can_send_a_command_to_sonic_and_returns_a_response_object()
    {
        $this->client->connect();
        $this->client->clearBuffer();

        $this->assertInstanceOf(Response::class, $this->client->send(new PingCommand));
    }

}