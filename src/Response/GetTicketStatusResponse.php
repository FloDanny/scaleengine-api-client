<?php
namespace FloSports\ScaleEngine\Response;

use FloSports\ScaleEngine\Model\Factory\ScaleEngineTicketFactory;
use Guzzle\Service\Command\OperationCommand;

/**
 * Provides the ability to convert a command response into a ScaleEngineTicket
 * model.
 */
class GetTicketStatusResponse extends AbstractScaleEngineResponse
{
    /**
     * Get the response from the command as a ScaleEngineTicket model.
     *
     * This takes the command given, ensures that it has a successful response,
     * and converts the response into a ScaleEngineTicket model.
     *
     * @param OperationCommand $command The GetTicketStatus API call made.
     * @param ScaleEngineTicketFactory $modelFactory The factory to use to
     *     create tickets.  Will be created if not given.
     * @return ScaleEngineTicket The ticket returned from the API request.
     */
    public static function fromCommand(OperationCommand $command, ScaleEngineTicketFactory $modelFactory = null)
    {
        $modelFactory = $modelFactory ?: new ScaleEngineTicketFactory();

        return $modelFactory->create(self::getResult($command));
    }
}
