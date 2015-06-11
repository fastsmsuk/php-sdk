<?php

use FastSMS\Client;
use FastSMS\Exception\ApiException;

require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/config.php';

$client = new Client($config['token']);

try {
    $balance = $client->credits->balance;
    echo number_format($balance, 2);
} catch (ApiException $aex) {
    echo 'API error #' . $aex->getCode() . ': ' . $aex->getMessage();
} catch (Exception $ex) {
    echo $ex->getMessage();
}

