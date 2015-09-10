<?php

use FastSMS\Client;
use FastSMS\Model\Message;
use FastSMS\Exception\ApiException;

require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/config.php';

#init client
$client = new Client($config['token']);

#####################################
#Send message direct phone number(s)#
#####################################
// Init Message data model
$data = [
    'destinationAddress' => $config['destinations'],
    'sourceAddress' => $config['source'],
    'body' => 'Test API Wraper',
    'scheduleDate' => time() + 7200,
    'validityPeriod' => 3600 * 6,
];

// Send Message
try {
    $result1 = $client->message->send($data);
    print_r($result1);
    /*
     * Example return:
     * Array
     * (
     *    [type] => direct
     *    [send] => success
     *    [messages] => Array
     *    (
     *        [0] => 55379665
     *        [1] => 55379666
     *     )
     * )
     */
} catch (ApiException $aex) {
    echo 'API error #' . $aex->getCode() . ': ' . $aex->getMessage();
} catch (Exception $ex) {
    echo $ex->getMessage();
}

#####################################
########Send message to list#########
#####################################
$data = [
    'list' => $config['list'],
    'destinationAddress' => $config['destinations'],
    'sourceAddress' => $config['source'],
    'body' => 'Test API Wraper',
    'scheduleDate' => time() + 7200,
    'validityPeriod' => 3600 * 6,
];
// Send Message
try {
    $result2 = $client->message->send($data);
    print_r($result2);
    /*
     * Array
     * (
     *     [type] => list
     *     [send] => success
     * )
     */
} catch (ApiException $aex) {
    echo 'API error #' . $aex->getCode() . ': ' . $aex->getMessage();
} catch (Exception $ex) {
    echo $ex->getMessage();
}


#####################################
########Send message to group########
#####################################
$data = [
    'group' => $config['group'],
    'sourceAddress' => $config['source'],
    'body' => 'Test group',
    'scheduleDate' => time() + 7200,
    'validityPeriod' => 3600 * 6,
];
// Send Message
try {
    $result3 = $client->message->send($data);
    print_r($result3);
    /*
     * Array
     * (
     *     [type] => group
     *     [send] => success
     * )
     */
} catch (ApiException $aex) {
    echo 'API error #' . $aex->getCode() . ': ' . $aex->getMessage();
} catch (Exception $ex) {
    echo $ex->getMessage();
}