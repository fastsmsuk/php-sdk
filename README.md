PHP SDK for FastSMS API.
===========================
PHP library to access [FastSMS](http://www.fastsms.co.uk/) api.
Thank you for choosing [FastSMS](http://www.fastsms.co.uk/)<br/>
[![Latest Stable Version](https://poser.pugx.org/fastsms/sdk/v/stable)](https://packagist.org/packages/fastsms/sdk)
[![Total Downloads](https://poser.pugx.org/fastsms/sdk/downloads)](https://packagist.org/packages/fastsms/sdk)
[![Latest Unstable Version](https://poser.pugx.org/fastsms/sdk/v/unstable)](https://packagist.org/packages/fastsms/sdk)
[![License](https://poser.pugx.org/fastsms/sdk/license)](https://packagist.org/packages/fastsms/sdk)
[![Build Status](https://travis-ci.org/fastsmsuk/php-sdk.svg)](https://travis-ci.org/fastsmsuk/php-sdk)
DIRECTORY STRUCTURE
-------------------

```
src/                 core wrapper code
tests/               tests of the core wrapper code
```

REQUIREMENTS
------------

The minimum requirement by FastSMS wrapper is that your Web server supports PHP 5.4.

DOCUMENTATION
-------------
FastSMS has a [Knowledge Base](http://support.fastsms.co.uk/knowledgebase/) and 
a [Developer Zone](http://support.fastsms.co.uk/knowledgebase/category/developer-zone/) which cover every detail of FastSMS API.

INSTALLATION
-------------
Add to composer
```js
"require": {
    ...
    "fastsms/sdk": "*",
    ...
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
    $credits = $client->credits->getBalance();
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
    'scheduleDate' => time() + 7200, //now + 2h
    'validityPeriod' => 3600 * 6, //maximum 86400 = 24 hours
];
$result = $client->message->send($data);
```

### Check message status
Check send message status. More information read [this](http://support.fastsms.co.uk/knowledgebase/http-documentation/#CheckMessageStatus)
```
$result = $client->message->status($messageId);// Message Id must be integer
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
$result = $client->user->create($data);
```

### Update Credits
Transfer credits to/from a child user. Only possible if you are an admin user. More information read [this](http://support.fastsms.co.uk/knowledgebase/http-documentation/#UpdateCredits)
```
...
use FastSMS\Model\User;
// Init update user data
$data = [
    'childUsername' => 'Exist Username',
    'quantity' => -5, //The amount of credits to transfer.
];
$user = new User($data);
$result = $client->user->update($user);
```

### Reports
Retrieve the data from a report. More information read [this](http://support.fastsms.co.uk/knowledgebase/http-documentation/#Reports)
Aviable types:
- ArchivedMessages
- ArchivedMessagesWithBodies
- ArchivedMessagingSummary
- BackgroundSends
- InboundMessages
- KeywordMessageList
- Messages
- MessagesWithBodies
- SendsByDistributionList
- Usage
```
...
use FastSMS\Model\Report;
...
// Init Report params
$data = [
    'reportType' => 'Messages',
    'from' => time() - 3600 * 24 * 30,
    'to' => time()
];
// Get report
$result = $client->report->get($data);
```

### Add contact(s)
Create contact(s). More information read [this](http://support.fastsms.co.uk/knowledgebase/http-documentation/#ImportContactsCSV)
```
...
use FastSMS\Model\Contact;
...
// Init Contacts data
$data = [
    'contacts' => [
        ['name' => 'John Doe 1', 'number' => 15417543011, 'email' => 'john.doe.1@example.com'],
        ['name' => 'John Doe 2', 'number' => 15417543012, 'email' => 'john.doe.2@example.com'],
        ['name' => 'John Doe 3', 'number' => 15417543013, 'email' => 'john.doe.3@example.com'],
    ],
    'ignoreDupes' => false,
    'overwriteDupes' => true
];
// Get report
$result = $client->contact->create($data);
```

### Delete All Contacts
More information read [this](http://support.fastsms.co.uk/knowledgebase/http-documentation/#DeleteAllContacts)
```
...
use FastSMS\Model\Contact;
...
// Get report
$result = $client->contact->deleteAll();
```

### Delete All Groups
More information read [this](http://support.fastsms.co.uk/knowledgebase/http-documentation/#DeleteAllGroups)
```
...
use FastSMS\Model\Contact;
...
// Get report
$result = $client->group->deleteAll();
```

### Empty Group
Remove all contacts from the specified group. More information read [this](http://support.fastsms.co.uk/knowledgebase/http-documentation/#EmptyGroup)
```
...
use FastSMS\Model\Contact;
...
// Get report
$result = $client->group->empty('Group Name');
```

### Delete Group
Delete the specified group. More information read [this](http://support.fastsms.co.uk/knowledgebase/http-documentation/#DeleteGroup)
```
...
use FastSMS\Model\Contact;
...
// Get report
$result = $client->group->delete('Group Name');
```
