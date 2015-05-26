PHP SDK for FastSMS API.
===========================
PHP library to access [FastSMS](http://www.fastsms.co.uk/) api.
Thank you for choosing [FastSMS](http://www.fastsms.co.uk/)

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
#!php5
#
$FastSMS = new Netsecrets\FastSMS\FastSMS('your token');
```
or
```
#!php5
#
use Netsecrets\FastSMS\FastSMS;
...
$FastSMS = new FastSMS('your token');
...
```

Actions
-------------
### Check Credits
Checks your current credit balance.
```
use Netsecrets\FastSMS\FastSMS;
$FastSMS = new FastSMS('your token');
$credits = $FastSMS->checkCredits(); //return float val
echo number_format($credits, 2); //example show 1,000.00
```