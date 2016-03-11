<?php
namespace FloSports\ScaleEngine;

use Exception;
use FloSports\ScaleEngine\CommandFactory\ScaleEngineCommandFactory;
use Guzzle\Service\Client as GuzzleService;
use Guzzle\Service\Description\ServiceDescription;

/**
 * A Guzzle service client tailored for ScaleEngine's API.
 *
 * You can get a list of operations supported by calling
 * $scaleEngineClient->getDescription()->getOperations();
 * or looking at the service description JSON.
 */
class ScaleEngineClient extends GuzzleService
{
    /** @type string The expected date format ScaleEngine uses. **/
    const SCALEENGINE_DATE_FORMAT = 'Y-m-d H:i:s';

    /** @type string The full date string ScaleEngine uses for a null date. **/
    const SCALEENGINE_NULL_DATE = '0000-00-00 00:00:00';

    /** @type string The timezone ScaleEngine uses for its dates. **/
    const SCALEENGINE_TIMEZONE = 'UTC';

    /**
     * Construct a service client for ScaleEngine's API.
     *
     * Loads the standard service description for ScaleEngine's API endpoints
     * and initializes the commands with a visitor that can properly encode
     * requests to ScaleEngine's API including request signing.
     *
     * @param array $config {
     *     @var string apiSecret (required) The API secret used to sign
     *         requests.
     *     @var array command.params {
     *         @var string apiKey The secret API key used to authenticate with
     *             your ScaleEngine account.
     *         @var int cdn The CDN id of your ScaleEngine account.
     *     }
     * }
     */
    public static function factory($config = [])
    {
        if (!array_key_exists('apiSecret', $config)) {
            throw new Exception('Missing apiSecret for ScaleEngine API.');
        }

        $client = parent::factory($config);

        $description = ServiceDescription::factory(__DIR__ . '/service/main.json');
        $client->setCommandFactory(
            new ScaleEngineCommandFactory($config['apiSecret'], $description)
        );
        $client->setDescription($description);

        return $client;
    }
}
