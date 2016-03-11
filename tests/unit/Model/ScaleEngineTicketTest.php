<?php
namespace FloSports\ScaleEngine\Model;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \FloSports\ScaleEngine\Model\ScaleEngineTicket
 */
class ScaleEngineTicketTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that isEqual works when the keys are equal.
     *
     * @test
     * @covers ::isEqual
     */
    public function isEqualTrue()
    {
        $modelA = new ScaleEngineTicket(['key' => 'foo', 'other' => 'x']);
        $modelB = new ScaleEngineTicket(['key' => 'foo', 'other' => 'y']);

        $this->assertTrue($modelA->isEqual($modelB));
    }

    /**
     * Verify that isEqual works when the keys are not equal.
     *
     * @test
     * @covers ::isEqual
     */
    public function isEqualFalse()
    {
        $modelA = new ScaleEngineTicket(['key' => 'foo', 'other' => 'x']);
        $modelB = new ScaleEngineTicket(['key' => 'bar', 'other' => 'y']);

        $this->assertFalse($modelA->isEqual($modelB));
    }
}
