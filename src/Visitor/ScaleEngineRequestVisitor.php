<?php
namespace FloSports\ScaleEngine\Visitor;

use Guzzle\Http\Message\RequestInterface;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Command\LocationVisitor\Request\AbstractRequestVisitor;
use Guzzle\Service\Description\Parameter;
use SplObjectStorage;

/**
 * Visitor used to apply a parameter to an array that will be signed and
 * serialized as a POST field named json in the response in accordance with
 * ScaleEngine's API format.
 */
class ScaleEngineRequestVisitor extends AbstractRequestVisitor
{
    /** @type \SplObjectStorage Data object for persisting JSON data. */
    private $_storage;

    /** @type string The API secret used to sign requests. */
    private $_apiSecret;

    /**
     * Create the visitor, initializing the SPL Object Storage.
     *
     * @param string $apiSecret The API secret used to sign requests.
     */
    public function __construct($apiSecret)
    {
        $this->_storage = new SplObjectStorage();
        $this->_apiSecret = $apiSecret;
    }

    /**
     * Handle a single parameter for a command.
     *
     * Each command has it's own storage inside the SPL Object Storage.  This
     * adds the parameter/value to the storage for that command, after
     * processing any filters, etc. for the parameter.
     *
     * @param CommandInterface $command The command the parameter is for.
     * @param RequestInterface $request The HTTP request being prepared.
     *     Unused.
     * @param Parameter $param The parameter definition being set.
     * @param mixed $value The value the parameter is being set to.
     * @return void
     */
    public function visit(CommandInterface $command, RequestInterface $request, Parameter $param, $value)
    {
        $data = isset($this->_storage[$command]) ? $this->_storage[$command] : [];
        $data[$param->getWireName()] = $this->prepareValue($value, $param);
        $this->_storage[$command] = $data;
    }

    /**
     * Finalizes the data for a request and sets it in the response.
     *
     * ScaleEngine requires a signature of the request to be added to the
     * request.  The data is then JSON-encoded and shoved into a POST field
     * named `json`.
     *
     * @param CommandInterface $command The command being sent.
     * @param RequestInterface $request The HTTP request being prepared.  Will
     *     be modified with the serialized data added as a POST field.
     * @return void
     */
    public function after(CommandInterface $command, RequestInterface $request)
    {
        if (!isset($this->_storage[$command])) {
            return;
        }

        $data = $this->_storage[$command];
        unset($this->_storage[$command]);

        $data['timestamp'] = isset($data['timestamp']) ? $data['timestamp'] : time();
        $data['signature'] = $this->_getSignature($data);

        $request->setPostField('json', json_encode($data));
    }

    /**
     * Gets the signature of the data using ScaleEngine's algorithm.
     *
     * This is a potentially fragile method as it could break if `json_encode`
     * changes.
     *
     * @param array $data The data from the request to sign.
     * @return string The base64-encoded signature of the data.
     */
    private function _getSignature(array $data)
    {
        $json = json_encode($data);

        return base64_encode(hash_hmac('sha256', $json, $this->_apiSecret, true));
    }
}
