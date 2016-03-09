<?php
namespace FloSports\ScaleEngine\Response;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \FloSports\ScaleEngine\Response\RevokeTicketResponse
 */
class RevokeTicketResponseTest extends PHPUnit_Framework_TestCase
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
        $replyData = ['status' => 'success'];

        $mockCommand = $this->_getMockCommandThatReturnsData($replyData);

        $result = RevokeTicketResponse::fromCommand($mockCommand);

        $this->assertNull($result, 'Failed to get back the expected response.');
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
