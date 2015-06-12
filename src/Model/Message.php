<?php

namespace FastSMS\Model;

/**
 * Create message.
 * @link http://support.fastsms.co.uk/knowledgebase/http-documentation/#SendMessage Docs
 */
class Message extends BaseModel
{

    /**
     * Destination Address(es) to send the message to.
     * @var string|array 
     */
    public $destinationAddress;

    /**
     * Destination list.
     * @var array 
     */
    public $list;

    /**
     * Destination group.
     * @var array 
     */
    public $group;

    /**
     * Source Address for the message.
     * @var string
     */
    public $sourceAddress;

    /**
     * Message Body
     * @var string
     */
    public $body;

    /**
     * The date that the message should be sent.
     * @var integer Timestamp
     */
    public $scheduleDate;

    /**
     * The period in seconds that the message will be tried for (maximum 86400 = 24 hours).
     * @var integer Seconds
     */
    public $validityPeriod;

    /**
     * If the message is longer than 160 characters, the system by default will return only the first ID 
     * for each message. Set this parameter to 1 to return all the Ids for each part of each message.
     * @var integer
     */
    private $getAllMessageIDs = 1;

    /**
     * @inheritdoc
     */
    public function buildArgs()
    {
        // Set destination
        if ($this->list && !empty($this->list) && is_string($this->list)) {
            $args['DestinationAddress'] = 'List';
            $args['ListName'] = $this->list;
        }
        if ($this->group && !empty($this->group) && is_string($this->group)) {
            $args['DestinationAddress'] = 'Group';
            $args['GroupName'] = $this->group;
        }
        if ($this->destinationAddress && !$this->list && !$this->group) {
            if (is_string($this->destinationAddress) || is_integer($this->destinationAddress)) {
                $args['DestinationAddress'] = $this->destinationAddress;
            } elseif (is_array($this->destinationAddress)) {
                $args['DestinationAddress'] = implode(',', $this->destinationAddress);
            }
        }
        // Set source
        if ($this->sourceAddress && !empty($this->sourceAddress) &&
            (is_string($this->sourceAddress) || is_integer($this->sourceAddress))) {
            $args['SourceTON'] = 5;
            if (is_integer($this->sourceAddress) && strlen($this->sourceAddress) >= 11) {
                $args['SourceTON'] = 1;
            }
            $args['SourceAddress'] = $this->sourceAddress;
        }
        // Set body
        if ($this->body && !empty($this->body) && is_string($this->body)) {
            $args['Body'] = $this->body;
        }
        // Set schedule
        if ($this->scheduleDate && !empty($this->scheduleDate) && is_integer($this->scheduleDate)) {
            $date = new \DateTime("@".$this->scheduleDate);
            $date->setTimezone(new \DateTimeZone('Europe/London'));
            $args['ScheduleDate'] = $date->format('YmdHis');
        }
        // Set validity
        if ($this->validityPeriod && is_integer($this->validityPeriod) &&
            $this->validityPeriod > 0 && $this->validityPeriod < 86400) {
            $args['ValidityPeriod'] = $this->validityPeriod;
        }
        $args['GetAllMessageIDs'] = $this->getAllMessageIDs;
        return $args;
    }

}
