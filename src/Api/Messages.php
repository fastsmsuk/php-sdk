<?php

namespace FastSMS\Api;

use FastSMS\Model\Message;

/**
 * This is the API class for Messages
 */
class Messages extends AbstractApi
{

    /**
     * Send message
     * @param \FastSMS\Model\Message $message Message model
     * @return mixed
     */
    public function send(Array $data)
    {
        $args = (new Message($data))->buildArgs();
        $result = [];
        $data = $this->client->http->call('Send', $args);
        $result['type'] = '';
        $result['send'] = 'error';
        if (isset($args['GroupName']) && $data == 1) {
            $result['type'] = 'group';
            $result['send'] = 'success';
        } elseif (isset($args['ListName']) && $data == 1) {
            $result['type'] = 'list';
            $result['send'] = 'success';
        } elseif ($data != 1) {
            $result['type'] = 'direct';
            $result['send'] = 'success';
            $result['messages'] = explode(',', $data);
        }
        return $result;
    }

    /**
     * Get exist message status.
     * @param int $messageID Exist message ID
     */
    public function status($messageID)
    {
        $args = [];
        $result = [];
        if ($messageID && !empty($messageID) && is_integer($messageID)) {
            $args['MessageID'] = $messageID;
        }
        $data = $this->client->http->call('CheckMessageStatus', $args);
        $result['status'] = $data;
        return $result;
    }

}
