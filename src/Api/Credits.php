<?php

namespace FastSMS\Api;

/**
 * This is the API class for Credits
 *
 * @property float $balance Current credit balance.
 *
 * @property Categories $parent
 * @property Categories[] $categories
 */
class Credits extends AbstractApi
{

    protected $balance;

    /**
     * Checks your current credit balance.
     * @return float
     */
    public function getBalance()
    {
        if (!$this->balance) {
            $this->balance = floatval($this->client->http->call('CheckCredits'));
        }
        return $this->balance;
    }

}
