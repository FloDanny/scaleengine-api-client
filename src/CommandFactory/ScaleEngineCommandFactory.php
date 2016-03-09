<?php
namespace FloSports\ScaleEngine\CommandFactory;

use FloSports\ScaleEngine\Visitor\ScaleEngineRequestVisitor;
use Guzzle\Service\Command\Factory\ServiceDescriptionFactory;
use Guzzle\Service\Description\ServiceDescriptionInterface;
use Guzzle\Inflection\InflectorInterface;

/**
 * A Guzzle command factory that extends commands to work against the
 * ScaleEngine API.
 */
class ScaleEngineCommandFactory extends ServiceDescriptionFactory
{
    /** @type string The API secret used to sign requests. */
    private $_apiSecret;

    /**
     * Create the command factory for the ScaleEngine API requests.
     *
     * @param string $apiSecret The API secret used to sign requests.
     * @param ServiceDescriptionInterface $description Service description.
     * @param InflectorInterface $inflector Optional inflector to use if the
     *     command is not at first found.
     */
    public function __construct(
        $apiSecret,
        ServiceDescriptionInterface $description,
        InflectorInterface $inflector = null
    ) {
        parent::__construct($description, $inflector);
        $this->_apiSecret = $apiSecret;
    }

    /**
     * Create a Guzzle command using the applied service description.
     *
     * Extends the command created by the default ServiceDescriptionFactory
     * behavior with a request visitor that signs and encodes requests to
     * ScaleEngine's API.
     *
     * @see \FloSports\ScaleEngine\Visitor\ScaleEngineRequestVisitor
     * @param string $name The name of the command to create.
     * @param array $args The parameters to the command.
     * @return \Guzzle\Service\Command\CommandInterface|null The constructed
     *     command.
     */
    public function factory($name, array $args = [])
    {
        $command = parent::factory($name, $args);
        if (!$command) {
            return null;
        }

        $command->getRequestSerializer()->addVisitor(
            'scaleengineParameter',
            new ScaleEngineRequestVisitor($this->_apiSecret)
        );

        return $command;
    }
}
