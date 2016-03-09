<?php
namespace FloSports\ScaleEngine\Model\Factory;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \FloSports\ScaleEngine\Model\Factory\AbstractScaleEngineModelFactory
 */
class AbstractScaleEngineModelFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that convert date correctly converts a date to a timestamp.
     *
     * @test
     * @covers ::convertDate
     */
    public function convertDate()
    {
        $date = '2016-03-08 17:52:01';
        $expected = 1457459521;

        $factory = $this->getMockBuilder('\FloSports\ScaleEngine\Model\Factory\AbstractScaleEngineModelFactory')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $result = $factory->convertDate($date);

        $this->assertSame($expected, $result->getTimestamp());
    }

    /**
     * Verify that convert date recognizes a null date.
     *
     * @test
     * @covers ::convertDate
     */
    public function convertDateNull()
    {
        $date = '0000-00-00 00:00:00';

        $factory = $this->getMockBuilder('\FloSports\ScaleEngine\Model\Factory\AbstractScaleEngineModelFactory')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $result = $factory->convertDate($date);

        $this->assertNull($result);
    }
}
