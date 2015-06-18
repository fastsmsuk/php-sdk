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
        $this->sdk = new Client();
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Test is load HTTP library
     */
    public function testLoadHTTPLibrary()
    {
        $this->assertArrayHasKey($this->sdk->http->getHTTPLibrary(), Http::getSupportLibraries());
    }

}
