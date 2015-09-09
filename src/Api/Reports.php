<?php

namespace FastSMS\Api;

use FastSMS\Model\Report;

/**
 * This is the API class for Reports
 *
 */
class Reports extends AbstractApi
{

    /**
     * Send message
     * @param \FastSMS\Model\Report $report report model
     * @return mixed
     */
    public function get(Array $data)
    {
        $args = (new Report($data))->buildArgs();
        $data = [];
        $result = [];
        $csv = $this->client->http->call('Report', $args);
        $arr = explode("\n", $csv);
        foreach ($arr as $line) {
            if (!empty($line)) {
                $data[] = str_getcsv($line);
            }
        }
        $keys = $data[0];
        unset($data['0']);
        foreach ($data as $values) {
            $result[] = array_combine($keys, $values);
        }
        return $result;
    }

}
