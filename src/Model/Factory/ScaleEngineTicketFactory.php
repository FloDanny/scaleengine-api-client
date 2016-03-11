<?php
namespace FloSports\ScaleEngine\Model\Factory;

/**
 * A factory used to construct ScaleEngine ticket models.
 */
class ScaleEngineTicketFactory extends AbstractScaleEngineModelFactory
{
    /**
     * Create the model factory for ScaleEngine tickets.
     */
    public function __construct()
    {
        parent::__construct(
            '\FloSports\ScaleEngine\Model\ScaleEngineTicket',
            $this->_keyMap()
        );
    }

    /**
     * Parse the data given into correct types and changes keys based on the
     * key map.
     *
     * @param array $data The ticket data to parse.
     * @return array The data after being converted to the parsed form.
     */
    public function parseData(array $data)
    {
        $parsedData = [];
        foreach ($this->_keyMap() as $from => $to) {
            if (array_key_exists($from, $data)) {
                $parsedData[$to] = $data[$from];
            }
        }

        $parsedData['active'] = (bool)$parsedData['active'];
        $parsedData['createdDate'] = $this->convertDate($parsedData['createdDate']);
        $parsedData['usedDate'] = $this->convertDate($parsedData['usedDate']);
        $parsedData['uses'] = (int)$parsedData['uses'];

        return $parsedData;
    }

    /**
     * Gets the key map for API results to the ScaleEngineTicket model.
     *
     * @return array A map of keys from ScaleEngine's API to exposed model.
     */
    private function _keyMap()
    {
        return [
            'active' => 'active',
            'app' => 'app',
            'created_date' => 'createdDate',
            'ip' => 'ip',
            'key' => 'key',
            'pass' => 'pass',
            'used_date' => 'usedDate',
            'uses' => 'uses',
            'video' => 'video',
        ];
    }
}
