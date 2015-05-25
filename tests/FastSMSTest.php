<?php

use Netsecrets\FastSMS\FastSMS;

class FastSMSTest extends PHPUnit_Framework_TestCase
{

    public function testNachHasCheese()
    {
        $nacho = new FastSMS;
        $this->assertTrue($nacho->hasCheese());
    }

}
