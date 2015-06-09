<?php

namespace FastSMS\Api;

use FastSMS\Object;
use FastSMS\Client;


abstract class AbstractApi extends Object
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
