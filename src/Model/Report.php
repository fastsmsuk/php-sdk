<?php

namespace FastSMS\Model;

/**
 * Create user.
 * @link http://support.fastsms.co.uk/knowledgebase/http-documentation/#Reports Docs
 */
class Report extends BaseModel
{

    /**
     * The Type of report.
     * (From and To dates are ignored for this report)
     * Aviable:
     * - ArchivedMessages
     * - ArchivedMessagesWithBodies
     * - ArchivedMessagingSummary
     * - BackgroundSends
     * - InboundMessages
     * - KeywordMessageList
     * - Messages
     * - MessagesWithBodies
     * - SendsByDistributionList
     * - Usage
     * @var string
     */
    public $reportType;

    /**
     * The start date for the report
     * @var integer Timestamp
     */
    public $from;

    /**
     * The end date for the report.
     * @var integer Timestamp
     * @var string
     */
    public $to;
    /**
     * Aviable report types
     * @var array
     */
    private $types = [
        'ArchivedMessages',
        'ArchivedMessagesWithBodies',
        'ArchivedMessagingSummary',
        'BackgroundSends',
        'InboundMessages',
        'KeywordMessageList',
        'Messages',
        'MessagesWithBodies',
        'SendsByDistributionList',
        'Usage',
    ];

    /**
     * @inheritdoc
     */
    public function buildArgs()
    {
        $args = [];
        if ($this->reportType && in_array($this->reportType, $this->types)) {
            $args['ReportType'] = $this->reportType;
        }
        if ($this->from && !empty($this->from) && is_integer($this->from)) {
            $date = new \DateTime("@" . $this->from);
            $date->setTimezone(new \DateTimeZone('Europe/London'));
            $args['From'] = $date->format('YmdHis');
        }
        if ($this->to && !empty($this->to) && is_integer($this->to)) {
            $date = new \DateTime("@" . $this->to);
            $date->setTimezone(new \DateTimeZone('Europe/London'));
            $args['To'] = $date->format('YmdHis');
        }
        return $args;
    }

}
