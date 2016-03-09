<?php
namespace FloSports\ScaleEngine\Model\Factory;

use DateTime;
use DateTimeZone;
use Guzzle\Service\Resource\Model;

/**
 * Collects shared functionality used by all ScaleEngine models.
 */
abstract class AbstractScaleEngineModelFactory
{
    /** @type string The expected date format ScaleEngine uses. **/
    const SCALEENGINE_DATE_FORMAT = 'Y-m-d H:i:s';

    /** @type string The full date string ScaleEngine uses for a null date. **/
    const SCALEENGINE_NULL_DATE = '0000-00-00 00:00:00';

    /** @type string The timezone ScaleEngine uses for its dates. **/
    const SCALEENGINE_TIMEZONE = 'UTC';

    /** @type string The model class name. **/
    private $_model;

    /** @type array A map of keys from ScaleEngine's API to exposed model. **/
    private $_keyMap;

    /**
     * Create the model factory for a specific type of model.
     *
     * @param string $model The full class name for the model to construct.
     * @param array $keyMap A map of keys from ScaleEngine's API to exposed model.
     */
    public function __construct($model, array $keyMap)
    {
        $this->_model = $model;
        $this->_keyMap = $keyMap;
    }

    /**
     * Create a Ticket model using the provided data.
     *
     * The data given will be parsed and any missing keys will result in
     * failure.
     *
     * @param array $data The data to use to populate the model.
     * @return \Guzzle\Service\Resource\Model The parsed model.
     */
    public function create(array $data)
    {
        $model = $this->_model;
        return $model::fromConfig($this->parseData($data), [], $this->_keyMap);
    }

    /**
     * Parse the data given into correct types and changes keys based on the
     * key map.
     *
     * @param array $data The ticket data to parse.
     * @return array The data after being converted to the parsed form.
     */
    abstract public function parseData(array $data);

    /**
     * Convert a date from ScaleEngine's format into a PHP DateTime.
     *
     * ScaleEngine uses an all-zeros response for null dates, and this will
     * convert those into null itself.
     *
     * This also considers that ScaleEngine uses UTC for its timezone and makes
     * sure that the time returned is correct considering that behavior.
     *
     * @param string $date The date as returned from ScaleEngine.
     * @return DateTime The converted date/time object.
     */
    public function convertDate($date)
    {
        if ($date === static::SCALEENGINE_NULL_DATE) {
            return null;
        }

        $dateTime = DateTime::createFromFormat(
            static::SCALEENGINE_DATE_FORMAT,
            $date,
            new DateTimeZone(static::SCALEENGINE_TIMEZONE)
        );

        return $dateTime ?: null;
    }
}
