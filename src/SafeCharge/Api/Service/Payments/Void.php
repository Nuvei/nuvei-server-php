<?php

namespace SafeCharge\Api\Service\Payments;

use SafeCharge\Api\RestClient;

/**
 * Class Void
 * @package SafeCharge\Api\Service\Payments
 * @deprecated Void is keyword in php 7.x. Please use TransactionAction class instead.
 */
class Void extends TransactionActions
{

    /**
     * Void constructor.
     * @param RestClient $client
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     */
    public function __construct(RestClient $client)
    {
        parent::__construct($client);
    }

}