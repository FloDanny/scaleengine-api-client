<?php
namespace FloSports\ScaleEngine\Response;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \FloSports\ScaleEngine\Response\RequestTicketResponse
 */
class RequestTicketResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that a successful reply returns the correct data.
     *
     * @test
     * @uses \FloSports\ScaleEngine\Response\AbstractScaleEngineResponse
     * @covers ::fromCommand
     */
    public function successfulReply()
    {
        $expected = 'baz';
        $replyData = ['status' => 'success', 'ticket' => ['foo' => 'bar']];

        $mockCommand = $this->_getMockCommandThatReturnsData($replyData);

        $mockFactory = $this->getMockBuilder('\FloSports\ScaleEngine\Model\Factory\ScaleEngineTicketFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockFactory
            ->expects($this->once())
            ->method('create')
            ->with($replyData['ticket'])
            ->will($this->returnValue($expected));

        $result = RequestTicketResponse::fromCommand($mockCommand, $mockFactory);

        $this->assertSame($expected, $result, 'Failed to get back the expected response.');
    }

    private function _getMockCommandThatReturnsData(array $data)
    {
        $mockResponse = $this->getMockBuilder('\Guzzle\Http\Message\Response')
            ->setMethods(['json'])
            ->disableOriginalConstructor()
            ->getMock();
        $mockResponse
            ->expects($this->once())
            ->method('json')
            ->will($this->returnValue($data));

        $mockCommand = $this->getMockBuilder('\Guzzle\Service\Command\OperationCommand')
            ->setMethods(['getResponse'])
            ->getMockForAbstractClass();
        $mockCommand
            ->expects($this->once())
            ->method('getResponse')
            ->will($this->returnValue($mockResponse));

        return $mockCommand;
    }
}
