<?php

namespace FastSMS\Api;

/**
 * This is the API class for Groups
 */
class Groups extends AbstractApi
{

    public function __call($method, $arguments) {
        if ($method == 'empty') {
            if (isset($arguments[0])){
                return $this->emptyGroup($arguments[0]);
            }
        }
    }

    /**
     * Delete All Groups
     * @return array
     */
    public function deleteAll()
    {
        $result = [];
        $data = $this->client->http->call('DeleteAllGroups', []);
        $result['status'] = 'error';
        if ($data == 1) {
            $result['status'] = 'success';
        }
        return $result;
    }

    /**
     * Remove all contacts from the specified group.
     * @param string $name Group name
     * @return array
     */
    public function emptyGroup($name)
    {
        $result = [];
        $args = [];
        if (is_string($name)) {
            $args['Group'] = $name;
        }
        $data = $this->client->http->call('EmptyGroup', $args);
        $result['status'] = 'error';
        if ($data == 1) {
            $result['status'] = 'success';
        }
        return $result;
    }

    /**
     * Delete the specified group.
     * @param string $name Group name
     * @return array
     */
    public function delete($name)
    {
        $result = [];
        $args = [];
        if (is_string($name)) {
            $args['Group'] = $name;
        }
        $data = $this->client->http->call('DeleteGroup', $args);
        $result['status'] = 'error';
        if ($data == 1) {
            $result['status'] = 'success';
        }
        return $result;
    }
}
