<?php

namespace Netsecrets\FastSMS;

class FastSMS
{

    /**
     * FastSMS API url
     * @var string URL
     */
    private $url = 'https://my.fastsms.co.uk/api';

    /**
     * HTTP library to use
     * @var string
     */
    private $library;

    /**
     * Secret token.
     * Found in your settings within NetMessenger.
     * 
     * @link https://my.fastsms.co.uk/account/settings look API section.
     * @var type 
     */
    private $token;

    public function __construct($token)
    {
        $this->token = $token;

        if (!$this->library) {
            $this->initHTTPLibrary();
        }
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
     * Make an API call
     */
    protected function call($call, $args = [])
    {
        $response = null;
        switch ($this->library) {
            case 'curl':
                $ch = curl_init();
                //set curl options
                curl_setopt($ch, CURLOPT_URL, $this->buildUrl($call));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                //exec query
                $response = curl_exec($ch);
                break;
            case 'openssl':
                $url = $this->call_url($call, []);
                $headers = "POST /api HTTP/1.0\r\n";
                $headers .= "Host: " . $url . "\r\n";
                $poststring = http_build_query($args, "", "&");
                $headers .= "Content-Length: " . strlen($poststring) . "\r\n";
                $headers .= "Content-Type: application/x-www-form-urlencoded\r\n";
                // Open a socket
                $http = fsockopen($url, 80, $err[0], $err[1]);
                if (!$http) {
                    echo "Connection to " . $url . ":80 failed: " . $err[0] . " (" . $err[1] . ")";
                    exit();
                }
                // Socket was open successfully, post the data.
                fwrite($http, $headers . "\r\n" . $poststring . "\r\n");
                // Read the results from the post
                $result = '';
                while (!feof($http)) {
                    $result .= fread($http, 8192);
                }
                // Close the connection
                fclose($http);
                // Strip the headers from the result
                list($resultheaders, $response) = preg_split("/\r\n\r\n/", $result, 2);
                unset($resultheaders);
                break;
            default:
                $response = file_get_contents($url);
        }
        return $response;
    }

    /**
     * Construct call URL
     * @param type $action Call action
     * @param type $args Aditional arguments
     * @return string
     */
    protected function buildUrl($action, $args = [])
    {
        $args['Action'] = $action;
        $url = $this->_url . '?' . http_build_query($this->buildArgs($args), "", "&");
        return $url;
    }

    /**
     * Add required api_* arguments
     * @param array $args
     * @return int
     */
    protected function buildArgs($args)
    {
        $args['Token'] = $this->token;
        $args['ShowErrorMessage'] = 1;
        return $args;
    }

    /**
     * Determine which HTTP library to use:
     * check for cURL, fsockopen, else fall back to file_get_contents
     */
    private function initHTTPLibrary()
    {
        switch (true) {
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
     * get all suport HTTP libraries to FastSMS SDK
     * @return array HTTP Libraries
     */
    public static function getSupportHTTPLibraries()
    {
        return [
            'curl' => 'сURL',
            'openssl' => 'OpenSSL',
            'fopen' => 'Base File System'
        ];
    }

}
