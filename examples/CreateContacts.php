<?php

use FastSMS\Client;
use FastSMS\Model\Contact;
use FastSMS\Exception\ApiException;

require __DIR__ . '/../vendor/autoload.php';
$config = require __DIR__ . '/config.php';

#init client
$client = new Client($config['token']);

#####################################
###########Create contacts###########
#####################################
// Init Contacts data model
$data = [
    'contacts' => [
        ['name' => 'John Doe 1', 'number' => 15417543011, 'email' => 'john.doe.1@example.com'],
        ['name' => 'John Doe 2', 'number' => 15417543012, 'email' => 'john.doe.2@example.com'],
        ['name' => 'John Doe 3', 'number' => 15417543013, 'email' => 'john.doe.3@example.com'],
    ],
    'ignoreDupes' => false,
    'overwriteDupes' => true
];
$contacts = new Contact($data);
// Create contacts
try {
    $result = $client->contact->create($contacts);
    print_r($result);
    /*
     * Example return:
     * array(3) {
     *     [1] => string(7) "Success"
     *     [2] => string(7) "Success"
     *     [3] => string(6) "Failed"
     * }
     */
} catch (ApiException $aex) {
    echo 'API error #' . $aex->getCode() . ': ' . $aex->getMessage();
} catch (Exception $ex) {
    echo $ex->getMessage();
}