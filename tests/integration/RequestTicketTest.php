<?php
namespace FloSports\ScaleEngine;

use Exception;
use PHPUnit_Framework_TestCase;

/**
 * Tests the sevu.request ScaleEngine API endpoint.
 */
class RequestTicketTest extends PHPUnit_Framework_TestCase
{
    private $_service;

    /**
     * Initialize the service client.
     *
     * @before
     */
    public function initializeServiceClient()
    {
        $apiSecret = getenv('TEST_SCALEENGINE_API_SECRET');
        $apiKey = getenv('TEST_SCALEENGINE_API_KEY');
        $cdn = (int)getenv('TEST_SCALEENGINE_CDN');

        if (!$apiSecret || !$apiKey || !$cdn) {
            throw new Exception(
                'Must set TEST_SCALEENGINE_API_SECRET, TEST_SCALEENGINE_API_KEY, and TEST_SCALEENGINE_CDN'
            );
        }

        $this->_service = ScaleEngineClient::factory([
            'apiSecret' => $apiSecret,
            'command.params' => ['apiKey' => $apiKey, 'cdn' => $cdn],
        ]);
    }

    /**
     * Verifies basic behavior for requestTicket calls.
     *
     * @test
     * @medium
     */
    public function basicBehavior()
    {
        $ticket = $this->_service->requestTicket($this->ticketRequest())->toArray();

        $this->assertSame('foo', $ticket['app']);
        $this->assertSame('auto/24', $ticket['ip']);
        $this->assertSame(10, $ticket['uses']);
        $this->assertSame('foo', $ticket['pass']);
        $this->assertSame('foo*', $ticket['video']);
        $this->assertSame(true, $ticket['active']);
        $this->assertNull($ticket['usedDate']);
        $this->assertRegexp('/^foo_*\.[a-f0-9]+\.\d+$/', $ticket['key']);

        // Ensure that the timestamp matches within 10 seconds
        $this->assertSame(time(), $ticket['createdDate']->getTimestamp(), 'Times not near eachother', 10);
    }

    /**
     * Verifies missing fields don't send a request.
     *
     * @test
     * @dataProvider requiredFieldProvider
     * @expectedException \Guzzle\Service\Exception\ValidationException
     */
    public function missingFields($fieldToRemove)
    {
        $data = array_diff_key($this->ticketRequest(), [$fieldToRemove => true]);
        $this->_service->requestTicket($data);
    }

    /**
     * Verifies invalid types don't send a request.
     *
     * @test
     * @dataProvider invalidTypesProvider
     * @expectedException \Guzzle\Service\Exception\ValidationException
     */
    public function invalidTypes($field, $value)
    {
        $data = $this->ticketRequest();
        $data[$field] = $value;

        $this->_service->requestTicket($data);
    }

    /**
     * Returns a sample request for a ticket.
     *
     * @return array A sample request to be passed to
     *     ScaleEngineClient::RequestTicket.
     */
    public function ticketRequest()
    {
        return [
            'app' => 'foo',
            'expires' => date('Y-m-d h:i:s', time() + 10),
            'ip' => 'auto/24',
            'uses' => 10,
            'pass' => 'foo',
            'video' => 'foo*',
        ];
    }

    /**
     * A provider used to call a test once with each required field.
     *
     * @return array The data provider data.
     */
    public function requiredFieldProvider()
    {
        return [['app'], ['expires'], ['ip'], ['uses'], ['pass'], ['video']];
    }

    /**
     * A provider used to call a test once with an invalid value for each
     * field.
     *
     * @return array The data provider data.
     */
    public function invalidTypesProvider()
    {
        return [
            ['app', 2.3],
            ['expires', 13.7],
            ['ip', false],
            ['uses', 'hello'],
            ['pass', 22.2],
            ['video', true],
        ];
    }
}
