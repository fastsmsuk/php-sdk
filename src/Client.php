<?php

namespace FastSMS;

use FastSMS\Api\Credits;
use FastSMS\Api\Messages;
use FastSMS\Exception\RuntimeException;

/**
 * This is the core class for FastSMS API wraper.
 *
 * @property Credits $credits Credits API
 * @property Messages $message Messages API
 */
class Client
{

    /**
     * Secret token.
     * Found in your settings within NetMessenger.
     * 
     * @link https://my.fastsms.co.uk/account/settings look API section.
     * @var type 
     */
    private $token = null;

    /**
     * Http client
     * @var \FastSMS\Http
     */
    public $http;

    /**
     * Create FastSMS object and set Auth data.
     * 
     * @param $api_token
     */
    public function __construct($api_token = null)
    {
        $this->token = $api_token;
        $this->http = new Http($this);
    }

    /**
     * @param  string $apiToken
     * @throws RuntimeException
     * @return $this
     */
    public function setToken($apiToken)
    {
        if (!is_string($apiToken)) {
            throw new RuntimeException('Api token must be set.');
        }
        $this->token = $apiToken;
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    /**
     * Call api components
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        switch ($name) {
            case 'credits':
                return new Credits($this);
            case 'message':
                return new Messages($this);
        }
    }

}
