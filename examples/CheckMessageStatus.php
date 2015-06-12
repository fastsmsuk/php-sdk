<?php

use FastSMS\Client;
use FastSMS\Exception\ApiException;

require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/config.php';

#init client
$client = new Client($config['token']);

#####################################
########Check Message Status#########
#####################################
// Get message status
try {
    $result = $client->message->status($config['existMessage']);
    print_r($result);
    /*
     * Example return:
     * Array
     * (
     *    [status] => Delivered
     * )
     */
} catch (ApiException $aex) {
    echo 'API error #' . $aex->getCode() . ': ' . $aex->getMessage();
} catch (Exception $ex) {
    echo $ex->getMessage();
}