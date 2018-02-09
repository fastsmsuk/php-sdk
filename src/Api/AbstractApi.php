<?php

namespace FastSMS\Api;

use FastSMS\FastSMSObject;
use FastSMS\Client;


abstract class AbstractApi extends FastSMSObject
{

    /**
     * API Client
     * @var \FastSMS\Client
     */
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    

}
