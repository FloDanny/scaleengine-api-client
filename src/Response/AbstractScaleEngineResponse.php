<?php
namespace FloSports\ScaleEngine\Response;

use Exception;
use Guzzle\Common\Collection;
use Guzzle\Service\Command\OperationCommand;

/**
 * A base ScaleEngine API response class.
 *
 * This provides ease of access to shared functionality among all ScaleEngine
 * responses.
 */
abstract class AbstractScaleEngineResponse
{
    /**
     * Get the result of a command, throwing an error if it failed.
     *
     * All ScaleEngine API calls have a top-level `status` field that will be
     * `success` for successful replies.  This will throw an exception if the
     * reply is not successful.
     *
     * @param OperationCommand $command The API call being executed.
     * @param string|array $resultPath The path to the actual result model.  If
     *     not provided, this will return the entire JSON response.
     * @return array The top-level result JSON from the API call.
     * @throws Exception if the result was not successful.
     */
    public static function getResult(OperationCommand $command, $resultPath = [])
    {
        $result = new Collection($command->getResponse()->json());
        if ($result['status'] !== 'success') {
            throw new Exception("Unsuccessful response from ScaleEngine: {$result['code']}: {$result['message']}");
        }

        return $result->getPath($resultPath);
    }
}
