<?php

namespace FastSMS;

use GuzzleHttp\Client as Guzzle;
use FastSMS\Exception\RuntimeException;
use FastSMS\Exception\ApiException;

class Http
{

    const SHEMA = 'https://';
    const TRANSPORT = 'ssl://';
    const URL = 'my.fastsms.co.uk';
    const PATH = '/api';

    /**
     * HTTP library to use
     * @var string
     */
    private $library;

    /**
     * API Client
     * @var \FastSMS\Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->initHTTPLibrary();
    }

    /**
     * get HTTP library to use
     * @return string HTTP library
     */
    public function getHTTPLibrary()
    {
        return $this->library;
    }

    /**
     * Construct call URL
     * @return string
     */
    protected function buildUrl()
    {
        return self::SHEMA . self::URL . self::PATH;
    }

    /**
     * Add required api_* arguments
     * @param string $action Call action
     * @param array $args Aditional arguments
     * @param boolean $array Return as array
     * @return string|array Params
     */
    protected function buildArgs($action, $args = [], $array = false)
    {
        $args['Action'] = $action;
        $args['Token'] = $this->client->getToken();
        $args['ShowErrorMessage'] = 0;
        if ($array) {
            return $args;
        }
        return http_build_query($args, "", "&");
    }

    /**
     * Check support Guzzle library.
     * @return boolean
     */
    private function checkGuzzleSupport()
    {
        if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
            if (ini_get('allow_url_fopen')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine which HTTP library to use:
     * check for cURL, fsockopen, else fall back to file_get_contents
     */
    private function initHTTPLibrary()
    {
        switch (true) {
            case $this->checkGuzzleSupport():
                $this->library = 'guzzle';
                break;
            case function_exists('curl_init'):
                $this->library = 'curl';
                break;
            case extension_loaded('openssl'):
                $this->library = 'openssl';
                break;
            default :
                $this->library = 'fopen';
        }
    }

    /**
     * Execute API call
     */
    public function call($action, $args = [])
    {
        $result = '';
        switch ($this->library) {
            case 'guzzle':
                $result = $this->guzzle($this->buildArgs($action, $args, true));
            case 'curl':
                $result = $this->curl($this->buildArgs($action, $args));
            case 'openssl':
                $result = $this->openssl($this->buildArgs($action, $args));
            default:
                $result = $this->basephp($this->buildArgs($action, $args));
        }
        if ($result < 0) {
            $message = self::getApiErrors($result);
            if ($message) {
                throw new ApiException($message, (int) -$result);
            }
            throw new ApiException('Unknown API error.', (int) -$result);
        }
        return $result;
    }

    /**
     * Call API used Guzzle
     * @param string $query Send data
     * @return mixed
     */
    protected function guzzle($query)
    {
        $client = new Guzzle();
        $result = $client->post($this->buildUrl(), ['form_params' => $query]);
        if (!$result || $result->getStatusCode() != 200) {
            throw new RuntimeException('Connection to ' . self::SHEMA . self::URL . ' failed.');
        }
        return $result->getBody()->getContents();
    }

    /**
     * Call API used Curl
     * @param string $query Send data
     * @return mixed
     */
    protected function curl($query)
    {
        $ch = curl_init();
        //set curl options
        curl_setopt($ch, CURLOPT_URL, $this->buildUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        //exec query
        $result = curl_exec($ch);
        if (!$result) {
            throw new RuntimeException('Connection to ' . self::SHEMA . self::URL . ' failed.');
        }
        return curl_exec($ch);
    }

    /**
     * Call API used OpenSSL
     * @param string $query Send data
     * @return mixed
     */
    protected function openssl($query)
    {
        // Init
        $err = [];
        $result = '';
        // Build headers
        $headers = "POST " . self::PATH . " HTTP/1.0\r\n";
        $headers .= "Host: " . self::URL . "\r\n";
        $headers .= "Content-Length: " . strlen($query) . "\r\n";
        $headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
        // Open a socket
        $http = fsockopen(self::TRANSPORT . self::URL, 443, $err[0], $err[1], 30);
        if (!$http) {
            throw new RuntimeException('Connection to ' . self::URL . ':443 failed: ' . $err[0] . ' (' . $err[1] . ')');
        }
        // Post data.
        fwrite($http, $headers . "\r\n" . $query . "\r\n");
        // Read results.
        while (!feof($http)) {
            $result .= fread($http, 8192);
        }
        // Close the connection
        fclose($http);
        // Strip the headers from the result
        list($resultheaders, $response) = preg_split("/\r\n\r\n/", $result, 2);
        unset($resultheaders);
        return $response;
    }

    /**
     * Call API used base file system PHP functions
     * @param string $action
     * @param array $args
     * @return mixed
     */
    protected function basephp($query)
    {
        $url = $this->buildUrl();
        $url .= '?' . $query;
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
        if (!$response) {
            throw new RuntimeException('Connection to ' . self::SHEMA . self::URL . ' failed.');
        }
        return $response;
    }

    /**
     * get all suport HTTP libraries
     * @return array HTTP Libraries
     */
    public static function getSupportLibraries()
    {
        return [
            'guzzle' => 'Guzzle',
            'curl' => 'сURL',
            'openssl' => 'OpenSSL',
            'fopen' => 'Base File System'
        ];
    }

    /**
     * FastSMS Errors
     * @param type $type
     * @param type $code
     * @return type
     */
    public static function getApiErrors($code = null)
    {
        $errors = [
            '-100' => 'Not Enough Credits',
            '-101' => 'Invalid CreditID',
            '-200' => 'Invalid Contact',
            '-300' => 'General Database Error',
            '-301' => 'Unknown Error',
            '-302' => 'Return XML Error',
            '-303' => 'Received XML Error',
            '-400' => 'Some numbers in list failed',
            '-401' => 'Invalid Destination Address',
            '-402' => 'Invalid Source Address – Alphanumeric too long',
            '-403' => 'Invalid Source Address – Invalid Number',
            '-404' => 'Blank Body',
            '-405' => 'Invalid Validity Period',
            '-406' => 'No Route Available',
            '-407' => 'Invalid Schedule Date',
            '-408' => 'Distribution List is Empty',
            '-409' => 'Group is Empty',
            '-410' => 'Invalid Distribution List',
            '-411' => 'You have exceeded the limit of messages you can send in a single day to a single number',
            '-412' => 'Number is blacklisted',
            '-414' => 'Invalid Group',
            '-501' => 'Unknown Username/Password',
            '-502' => 'Unknown Action',
            '-503' => 'Unknown Message ID',
            '-504' => 'Invalid From Timestamp',
            '-505' => 'Invalid To Timestamp',
            '-506' => 'Source Address Not Allowed (Email2SMS)',
            '-507' => 'Invalid/Missing Details',
            '-508' => 'Error Creating User',
            '-509' => 'Unknown/Invalid User',
            '-510' => 'You cannot set a user’s credits to be less than 0',
            '-511' => 'The system is down for maintenance',
            '-512' => 'User Suspended',
            '-513' => 'License in use',
            '-514' => 'License expired',
            '-515' => 'No License available',
            '-516' => 'Unknown List',
            '-517' => 'Unable to create List',
            '-518' => 'Blank or Invalid Source Address',
            '-519' => 'Blank Message Body',
            '-520' => 'Unknown Group',
            '-601' => 'Unknown Report Type',
            '-701' => 'No UserID Specified',
            '-702' => 'Invalid Amount Specified',
            '-703' => 'Invalid Currency Requested'
        ];
        if (isset($code)) {
            return isset($errors[$code]) ? $errors[$code] : false;
        }
        return $errors;
    }

}
