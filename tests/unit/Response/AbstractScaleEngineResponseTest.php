<?php
namespace FloSports\ScaleEngine\Response;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass \FloSports\ScaleEngine\Response\AbstractScaleEngineResponse
 */
class AbstractScaleEngineResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that a successful reply returns the correct data.
     *
     * @test
     * @covers ::getResult
     */
    public function successfulReply()
    {
        $replyData = ['status' => 'success', 'foo' => 'bar'];

        $mockCommand = $this->_getMockCommandThatReturnsData($replyData);

        $result = AbstractScaleEngineResponse::getResult($mockCommand, 'foo');

        $this->assertSame($replyData['foo'], $result, 'Failed to get back the expected response.');
    }

    /**
     * Verify that an errored reply throws the expected exception
     *
     * @test
     * @covers ::getResult
     * @expectedException Exception
     * @expectedExceptionMessage Unsuccessful response from ScaleEngine: 100: bad
     */
    public function failingReply()
    {
        $replyData = ['status' => 'failure', 'code' => 100, 'message' => 'bad'];

        $mockCommand = $this->_getMockCommandThatReturnsData($replyData);

        AbstractScaleEngineResponse::getResult($mockCommand, 'foo');
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
