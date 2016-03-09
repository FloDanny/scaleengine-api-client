<?php
namespace FloSports\ScaleEngine\Visitor;

use PHPUnit_Framework_TestCase;
use Guzzle\Service\Description\Parameter;

/**
 * @coversDefaultClass \FloSports\ScaleEngine\Visitor\ScaleEngineRequestVisitor
 */
class ScaleEngineRequestVisitorTest extends PHPUnit_Framework_TestCase
{
    /**
     * Verify that requests are signed and set properly on the request.
     *
     * Ensure that parameters that are visited during the request are included
     * in the serialized response.
     *
     * @test
     * @uses \Guzzle\Service\Description\Parameter
     * @covers ::__construct
     * @covers ::visit
     * @covers ::after
     * @covers ::_getSignature
     */
    public function signsRequest()
    {
        $mockCommand = $this->getMock('\Guzzle\Service\Command\CommandInterface');

        $mockRequest = $this->getMockBuilder('\Guzzle\Http\Message\RequestInterface')
            ->setMethods(['setPostField'])
            ->getMockForAbstractClass();
        $mockRequest
            ->expects($this->once())
            ->method('setPostField')
            ->with(
                $this->equalTo('json'),
                $this->stringContains('"foo":"bar"')
            );

        $parameter = new Parameter(['name' => 'foo']);

        $visitor = new ScaleEngineRequestVisitor('test');
        $visitor->visit($mockCommand, $mockRequest, $parameter, 'bar');
        $visitor->after($mockCommand, $mockRequest);
    }

    /**
     * Verify that sentAs is honored for parameters being sent.
     *
     * @test
     * @uses \Guzzle\Service\Description\Parameter
     * @covers ::__construct
     * @covers ::visit
     * @covers ::after
     * @covers ::_getSignature
     */
    public function sentAsParameters()
    {
        $mockCommand = $this->getMock('\Guzzle\Service\Command\CommandInterface');

        $mockRequest = $this->getMockBuilder('\Guzzle\Http\Message\RequestInterface')
            ->setMethods(['setPostField'])
            ->getMockForAbstractClass();
        $mockRequest
            ->expects($this->once())
            ->method('setPostField')
            ->with(
                $this->equalTo('json'),
                $this->stringContains('"foo":"bar"')
            );

        $parameter = new Parameter(['name' => 'notfoo', 'sentAs' => 'foo']);

        $visitor = new ScaleEngineRequestVisitor('test');
        $visitor->visit($mockCommand, $mockRequest, $parameter, 'bar');
        $visitor->after($mockCommand, $mockRequest);
    }

    /**
     * Verify that the request is unmodified when no parameters are set.
     *
     * @test
     * @uses \Guzzle\Service\Description\Parameter
     * @covers ::__construct
     * @covers ::visit
     * @covers ::after
     * @covers ::_getSignature
     */
    public function noParametersSentLeavesRequestAlone()
    {
        $visitor = new ScaleEngineRequestVisitor('test');

        $mockCommand = $this->getMock('\Guzzle\Service\Command\CommandInterface');
        $mockRequest = $this->getMockBuilder('\Guzzle\Http\Message\RequestInterface')
            ->getMockForAbstractClass();
        $mockRequest
            ->expects($this->never())
            ->method('setPostField');

        $visitor->after($mockCommand, $mockRequest);
    }
}
