<?php

use FastSMS\Client;
use FastSMS\Model\Report;
use FastSMS\Exception\ApiException;

require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/config.php';

#init client
$client = new Client($config['token']);

#####################################
#############Get report##############
#####################################
// Init Report params
$data = [
    'reportType' => 'Usage',
    'from' => time() - 3600 * 24 * 30,
    'to' => time()
];
$report = new Report($data);
// Get report
try {
    $result = $client->report->get($report);
    print_r($result);
    /*
     * Example return:
     * [0] => Array
     * (
     *     [Status] => Delivered
     *     [Messages] => 5
     * )
     * [1] => Array
     * (
     *     [Status] => Undeliverable
     *     [Messages] => 3
     * )
     */
} catch (ApiException $aex) {
    echo 'API error #' . $aex->getCode() . ': ' . $aex->getMessage();
} catch (Exception $ex) {
    echo $ex->getMessage();
}