<?php
namespace FloSports\ScaleEngine\Response;

use Guzzle\Service\Command\OperationCommand;

/**
 * Provides the ability to handle a ScaleEngine RevokeTicket command.
 */
class RevokeTicketResponse extends AbstractScaleEngineResponse
{
    /**
     * Get the response from the command as a ScaleEngineTicket model.
     *
     * This takes the command given and  ensures that it has a successful
     * response.  Because the revoke API call does not return any data, this
     * call does not return an object.
     *
     * @param OperationCommand $command The RevokeTicket API call made.
     * @return void
     */
    public static function fromCommand(OperationCommand $command)
    {
        self::getResult($command);
    }
}
