<?php

use FastSMS\Client;
use FastSMS\Http;

require_once __DIR__ . '/../vendor/autoload.php';

class FastSMSTest extends PHPUnit_Framework_TestCase
{

    /**
     * SDK object
     * @var \FastSMS\Client
     */
    private $sdk;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->sdk = new Client('6Rx6-stiU-a8sY-vt9i');
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Test is load HTTP library
     */
    public function testLoadHTTPLibrary()
    {
        $this->assertArrayHasKey($this->sdk->http->getHTTPLibrary(), Http::getSupportLibraries());
    }

    /**
     * Test Check Credits
     * Check is float type
     */
    public function testCheckCredits()
    {
        $balance = $this->sdk->credits->balance;
        $this->assertNotEmpty($balance);
        $this->assertInternalType('float', $balance);
    }

}
