<?php
namespace FloSports\ScaleEngine\Model;

use Guzzle\Service\Resource\Model;

/**
 * Represents a ticket from ScaleEngine's API.
 */
class ScaleEngineTicket extends Model
{
    /**
     * Compare this ticket against another to see whether it references the
     * same ticket in ScaleEngine.
     *
     * For the purposes of this comparison, only the key's have to match.
     *
     * @param self $other The other ticket to compare against.
     * @return boolean Whether the tickets reference the same ticket in
     *     ScaleEngine or not.
     */
    public function isEqual(ScaleEngineTicket $other)
    {
        return $this['key'] === $other['key'];
    }
}
