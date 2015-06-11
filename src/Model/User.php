<?php

namespace FastSMS\Model;

/**
 * Create user.
 * @link http://support.fastsms.co.uk/knowledgebase/http-documentation/#CreateUser Docs
 */
class User extends BaseModel
{

    /**
     * The username of the user you wish to create.
     * @var string
     */
    public $childUsername;

    /**
     * The password for the user.
     * @var string
     */
    public $childPassword;

    /**
     * What access level the user should have (Normal or Admin).
     * @var string
     */
    public $accessLevel;

    /**
     * The first name of the user.
     * @var string
     */
    public $firstName;

    /**
     * The last name of the user.
     * @var string
     */
    public $lastName;

    /**
     * The email address of the user.
     * @var string
     */
    public $email;

    /**
     * How many credits the user should start with (these will be deducted from your account).
     * @var integer
     */
    public $credits;
    
    /**
     * The amount of credits to transfer. If the number is positive, credits will be transferred to that user.
     * If the number is negative, they will be transferred from that user to you.
     * @var integer
     */
    public $quantity;

    /**
     * The telephone number of the user.
     * Optional
     * @var integer
     */
    public $telephone;

    /**
     * When to send the user a low credit warning email (number of credits left to trigger the email).
     * Optional
     * @var integer Credits
     */
    public $creditReminder;

    /**
     * After how many days of inactivity should an inactivity alert be sent.
     * Optional
     * @var integer Days
     */
    public $alert;

    /**
     * @inheritdoc
     */
    public function buildArgs()
    {
        $args = [];
        if ($this->childUsername && !empty($this->childUsername) && is_string($this->childUsername)) {
            $args['ChildUsername'] = $this->childUsername;
        }
        if ($this->childPassword && !empty($this->childPassword) && is_string($this->childPassword)) {
            $args['ChildPassword'] = $this->childPassword;
        }
        if ($this->accessLevel && ($this->accessLevel == 'Normal' || $this->accessLevel == 'Admin')) {
            $args['AccessLevel'] = $this->accessLevel;
        }
        if ($this->firstName && !empty($this->firstName) && is_string($this->firstName)) {
            $args['FirstName'] = $this->firstName;
        }
        if ($this->lastName && !empty($this->lastName) && is_string($this->lastName)) {
            $args['LastName'] = $this->lastName;
        }
        if ($this->email && !empty($this->email) && is_string($this->email)) {
            $args['Email'] = $this->email;
        }
        if ($this->credits && !empty($this->credits) && is_integer($this->credits)) {
            $args['Credits'] = $this->credits;
        }
        if ($this->quantity && !empty($this->quantity) && is_integer($this->quantity)) {
            $args['Quantity'] = $this->quantity;
        }
        if ($this->telephone && !empty($this->telephone) && is_integer($this->telephone)) {
            $args['Telephone'] = $this->telephone;
        }
        if ($this->creditReminder && !empty($this->creditReminder) && is_integer($this->creditReminder)) {
            $args['CreditReminder'] = $this->creditReminder;
        }
        if ($this->alert && !empty($this->alert) && is_integer($this->alert)) {
            $args['Alert'] = $this->alert;
        }
        return $args;
    }

}
