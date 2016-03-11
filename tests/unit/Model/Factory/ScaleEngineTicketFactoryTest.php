<?php
namespace FloSports\ScaleEngine\Model\Factory;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \FloSports\ScaleEngine\Model\Factory\ScaleEngineTicketFactory
 */
class ScaleEngineTicketFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that a standard ticket can be constructed.
     *
     * @test
     * @covers ::__construct
     * @covers ::_keyMap
     * @covers ::parseData
     * @covers \FloSports\ScaleEngine\Model\Factory\AbstractScaleEngineModelFactory::__construct
     * @covers \FloSports\ScaleEngine\Model\Factory\AbstractScaleEngineModelFactory::create
     */
    public function basicCreate()
    {
        $data = [
            'active' => '1',
            'app' => 'foo',
            'created_date' => '2016-03-08 17:52:01',
            'ip' => 'auto/24',
            'key' => 'bar',
            'pass' => 'secret',
            'used_date' => '0000-00-00 00:00:00',
            'uses' => '1000',
            'video' => 'vid',
        ];

        $factory = new ScaleEngineTicketFactory();

        $ticket = $factory->create($data)->toArray();

        $this->assertSame('foo', $ticket['app']);
        $this->assertSame('auto/24', $ticket['ip']);
        $this->assertSame(1000, $ticket['uses']);
        $this->assertSame('secret', $ticket['pass']);
        $this->assertSame('vid', $ticket['video']);
        $this->assertSame(true, $ticket['active']);
        $this->assertSame('bar', $ticket['key']);
        $this->assertSame(1457459521, $ticket['createdDate']->getTimestamp());
        $this->assertNull($ticket['usedDate']);
    }

    /**
     * Verify that a missing field in a ticket causes an error.
     *
     * @test
     * @covers ::__construct
     * @covers ::_keyMap
     * @covers ::parseData
     * @covers \FloSports\ScaleEngine\Model\Factory\AbstractScaleEngineModelFactory::__construct
     * @covers \FloSports\ScaleEngine\Model\Factory\AbstractScaleEngineModelFactory::create
     * @expectedException \Guzzle\Common\Exception\InvalidArgumentException
     * @expectedExceptionMessage Config is missing the following keys: app, key
     */
    public function missingFields()
    {
        $data = [
            'active' => '1',
            'created_date' => '2016-03-08 17:52:01',
            'ip' => 'auto/24',
            'pass' => 'secret',
            'used_date' => '0000-00-00 00:00:00',
            'uses' => '1000',
            'video' => 'vid',
        ];

        $factory = new ScaleEngineTicketFactory();

        $factory->create($data);
    }
}
