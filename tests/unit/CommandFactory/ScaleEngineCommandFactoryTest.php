<?php
namespace FloSports\ScaleEngine\CommandFactory;

use PHPUnit_Framework_TestCase;
use Guzzle\Service\Description\ServiceDescription;

/**
 * @coversDefaultClass \FloSports\ScaleEngine\CommandFactory\ScaleEngineCommandFactory
 */
class ScaleEngineCommandFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that the factory creates commands.
     *
     * Unfortunately, guzzle doesn't really implement this in a way that lets
     * us test that our visitor is added.  Because we have to subclass the
     * factory rather than use composition, we can't mock the code in the
     * factory itself and that means that we can't get a mock command in place.
     *
     * @test
     * @uses \FloSports\ScaleEngine\Visitor\ScaleEngineRequestVisitor
     * @covers ::__construct
     * @covers ::factory
     */
    public function buildsCommands()
    {
        $description = new ServiceDescription(['operations' => ['foo' => []]]);
        $factory = new ScaleEngineCommandFactory('test', $description);

        $command = $factory->factory('foo', []);

        $this->assertInstanceOf('\Guzzle\Service\Command\CommandInterface', $command);
    }

    /**
     * Verify that the factory returns null when the command can't be found.
     *
     * @test
     * @covers ::__construct
     * @covers ::factory
     */
    public function noCommandFound()
    {
        $description = new ServiceDescription(['operations' => []]);
        $factory = new ScaleEngineCommandFactory('test', $description);

        $command = $factory->factory('foo', []);

        $this->assertNull($command);
    }
}
