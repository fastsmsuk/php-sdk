<?php

namespace FastSMS\Api;

use FastSMS\Model\Message;

/**
 * This is the API class for Messages
 *
 * @property Categories $parent
 * @property Categories[] $categories
 */
class Messages extends AbstractApi
{

    /**
     * Send message
     * @param \FastSMS\Model\Message $message Message model
     * @return mixed
     */
    public function send(Message $message)
    {
        $args = $message->buildArgs();
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

}
