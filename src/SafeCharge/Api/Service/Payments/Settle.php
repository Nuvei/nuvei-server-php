<?php

namespace SafeCharge\Api\Service\Payments;

use SafeCharge\Api\RestClient;

/**
 * Class Settle
 * @package SafeCharge\Api\Service\Payments
 * @deprecated Please use TransactionAction class instead.
 */
class Settle extends TransactionActions
{

    /**
     * Settle constructor.
     * @param RestClient $client
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     */
    public function __construct(RestClient $client)
    {
        parent::__construct($client);
    }

}