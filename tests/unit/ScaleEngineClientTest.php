<?php
namespace FloSports\ScaleEngine;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \FloSports\ScaleEngine\ScaleEngineClient
 */
class ScaleEngineClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that the API client can return commands.
     *
     * Because we have to subclass the client rather than use composition, we
     * can't mock the code in the parent itself and that means that we can't
     * get a mock command in place.  This keeps us from being able to do real
     * unit testing and makes this more functional/integration testing.  Still
     * pretty safe though - only a small filesystem dependency that I'd really
     * ever expect to cause a problem.
     *
     * @test
     * @uses \FloSports\ScaleEngine\CommandFactory\ScaleEngineCommandFactory
     * @uses \FloSports\ScaleEngine\Visitor\ScaleEngineRequestVisitor
     * @covers ::factory
     */
    public function buildsCommands()
    {
        $client = ScaleEngineClient::factory(['apiSecret' => 'test']);
        $command = $client->getCommand('requestTicket');

        $this->assertInstanceOf('\Guzzle\Service\Command\CommandInterface', $command);
    }

    /**
     * Verify that an exception is thrown if no API secret is given.
     *
     * @test
     * @covers ::factory
     * @expectedException \Exception
     * @exceptionExceptionMessage Missing apiSecret for ScaleEngine API.
     */
    public function noSecret()
    {
        ScaleEngineClient::factory();
    }
}
