PHP SDK for FastSMS API.
===========================
PHP library to access [FastSMS](http://www.fastsms.co.uk/) api.
Thank you for choosing [FastSMS](http://www.fastsms.co.uk/)<br/>
[![Latest Stable Version](https://poser.pugx.org/netsecrets/fastsms/v/stable)](https://packagist.org/packages/netsecrets/fastsms)
[![Total Downloads](https://poser.pugx.org/netsecrets/fastsms/downloads)](https://packagist.org/packages/netsecrets/fastsms)
[![Latest Unstable Version](https://poser.pugx.org/netsecrets/fastsms/v/unstable)](https://packagist.org/packages/netsecrets/fastsms)
[![License](https://poser.pugx.org/netsecrets/fastsms/license)](https://packagist.org/packages/netsecrets/fastsms)
[![Build Status](https://travis-ci.org/LEZGROLLC/FastSMS-PHP-SDK.svg)](https://travis-ci.org/LEZGROLLC/FastSMS-PHP-SDK)
DIRECTORY STRUCTURE
-------------------

```
src/                 core wraper code
tests/               tests of the core wraper code
```

REQUIREMENTS
------------

The minimum requirement by FastSMS wraper is that your Web server supports PHP 5.4.

DOCUMENTATION
-------------
FastSMS has a [Knowledge Base](http://support.fastsms.co.uk/knowledgebase/) and 
a [Developer Zone](http://support.fastsms.co.uk/knowledgebase/category/developer-zone/) which cover every detail of FastSMS API.

INSTALLATION
-------------
Add to composer
```js
"require": {
    // ...
    "...":   "*",
    "...":   "*"
    // ...
}
```

USAGE
=============
Init SDK
-------------
Your token (found in your [settings](https://my.fastsms.co.uk/account/settings) within NetMessenger)
```
$FastSMS = new FastSMS\Client('your token');
```
or
```
use FastSMS\Client;
...
$FastSMS = new Client('your token');
...
```

Wrap errors
-------------
List all API codes found in [docs](http://support.fastsms.co.uk/knowledgebase/http-documentation/#ErrorCodes)
```
use FastSMS\Client;
use FastSMS\Exception\ApiException;
...
$client = new Client('your token');
try {
    $credits = $client->credits->balance;
} catch (ApiException $aex) {
    echo 'API error #' . $aex->getCode() . ': ' . $aex->getMessage();
} catch (Exception $ex) {
    echo $ex->getMessage();
}
```

Actions
-------------
### Credits
Checks your current credit balance.
```
$credits = $client->credits->balance; //return float val
echo number_format($credits, 2); //example show 1,000.00
```

### Send
Sends a message. More information read [this](http://support.fastsms.co.uk/knowledgebase/http-documentation/#SendMessage)
```
...
use FastSMS\Model\Message;
...
// Init Message data
$data = [
    //set
    'destinationAddress' => 'Phone number or multiple numbers in array',
    //or
    'list' => 'Your contacts list',
    //or
    'group' => 'Your contacts group'

    'sourceAddress' => 'Your Source Address',
    'body' => 'Message Body', //Note: max 459 characters
    //optionals
    'scheduleDate' => time() + 7200, //Note: This param must be Europe/London timezone
    'validityPeriod' => 3600 * 6, //maximum 86400 = 24 hours
];
$message = new Message($data);
$result = $client->message->send($message);
```

### Create User
Create new child user. Only possible if you are an admin user. More information read [this](http://support.fastsms.co.uk/knowledgebase/http-documentation/#CreateUser)
```
...
use FastSMS\Model\User;
// Init user data
$data = [
    'childUsername' => 'Username',
    'childPassword' => 'Password',
    'accessLevel' => 'Normal', //or 'Admin'
    'firstName' => 'John',
    'lastName' => 'Doe',
    'email' => 'user@example.com',
    'credits' => 100,
    //optionals
    'telephone' => 15417543010, // integer
    'creditReminder' => 10,
    'alert' => 5, //5 days
];
$user = new User($data);
$result = $client->user->create($user);