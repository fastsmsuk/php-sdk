<?php

use Netsecrets\FastSMS\FastSMS;

class FastSMSTest extends PHPUnit_Framework_TestCase
{

    /**
     * SDK object
     * @var \Netsecrets\FastSMS\FastSMS
     */
    private $sdk;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->sdk = new FastSMS('6Rx6-stiU-a8sY-vt9i');
        parent::__construct($name, $data, $dataName);
    }

    /**
     * Test is load HTTP library
     */
    public function testLoadHTTPLibrary()
    {
        $this->assertArrayHasKey($this->sdk->getHTTPLibrary(), FastSMS::getSupportHTTPLibraries());
    }

    /**
     * Test Check Credits
     * Check is float type
     */
    public function testCheckCredits()
    {
        $credits = $this->sdk->checkCredits();
        $this->assertNotEmpty($credits);
        $this->assertInternalType('float', $credits);
    }

}
