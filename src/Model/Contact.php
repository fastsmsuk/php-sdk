<?php

namespace FastSMS\Model;

/**
 * Create contact(s).
 * @link http://support.fastsms.co.uk/knowledgebase/http-documentation/#ImportContactsCSV Docs
 */
class Contact extends BaseModel
{

    /**
     * Contacts information.
     * Example:
     * [
     *     'name' => 'John Doe',
     *     'number' => '15417543010',
     *     'email' => 'example@domain.com',
     *     //optionals
     *     'group1' => 'Group1',
     *     'group2' => 'Group2',
     *     'group3' => 'Group3',
     * ]
     * @var array
     */
    public $contacts = [];
    /**
     * Contact Name
     * @var string
     */

    /**
     * If set to true, the system will allow duplicate contacts (name or number) to be created.
     * By default the system will not allow duplicate names or numbers in contacts.
     * 
     * @var boolean
     */
    public $ignoreDupes = false;

    /**
     * is set to true, the value of ignoreDupes is ignored.
     * is set to true, then any duplicate number will be overwritten. If a duplicate name exists,
     * but with a different number, the original contact will not be overwritten, instead a new contact will be created.
     * 
     * @var boolean
     */
    public $overwriteDupes = false;

    /**
     * @inheritdoc
     */
    public function buildArgs()
    {
        $args = [];
        $contacts = [];
        if (is_array($this->contacts) && count($this->contacts) > 0) {
            $iterator = 0;
            foreach ($this->contacts as $contact) {
                if (isset($contact['name'], $contact['number'], $contact['email']) && is_string($contact['name']) &&
                    is_integer($contact['number']) && is_string($contact['email'])) {
                    $contacts[$iterator] = [
                        $contact['name'],
                        $contact['number'],
                        $contact['email'],
                    ];
                }
                if (isset($contact['group1']) && is_string($contact['group1'])) {
                    $contacts[$iterator] = $contact['group1'];
                }
                if (isset($contact['group2']) && is_string($contact['group2'])) {
                    $contacts[$iterator] = $contact['group2'];
                }
                if (isset($contact['group3']) && is_string($contact['group3'])) {
                    $contacts[$iterator] = $contact['group3'];
                }
                $iterator ++;
            }
        }
        if (count($contacts) > 0) {
            // Create a stream opening it with read / write mode
            $stream = fopen('data://text/plain,' . "", 'w+');
            // Iterate over the data, writting each line to the text stream
            foreach ($contacts as $val) {
                fputcsv($stream, $val);
            }
            // Rewind the stream
            rewind($stream);
            // You can now echo it's content
            $args['ContactsCSV'] = trim(stream_get_contents($stream));
            // Close the stream 
            fclose($stream);
        }
        if (is_bool($this->ignoreDupes) && !$this->overwriteDupes) {
            $args['IgnoreDupes'] = $this->ignoreDupes ? 1 : 0;
        }
        if ($this->overwriteDupes === true) {
            $args['OverwriteDupes'] = 1;
        }
        return $args;
    }

}
