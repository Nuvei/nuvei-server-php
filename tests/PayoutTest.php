<?php

namespace Nuvei\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Nuvei\Api\Exception\ConfigurationException;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\Payments\Payout;

class PayoutTest extends TestCase
{
    private $service;

    /**
     * PayoutTest constructor.
     * @throws ConfigurationException
     */
    public function __construct()
    {
        parent::__construct();

        $this->service = new Payout(TestCaseHelper::getClient());
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testPayout ./tests/PayoutTest.php
     */
    public function testPayout()
    {
        $params = [
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientRequestId'   => '100',
            'clientUniqueId'    => '12345',
            'amount'            => "9.0",
            'currency'          => SimpleData::getCurrency(),
            'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
            'merchantDetails'   => SimpleData::getMerchantDetails(),
            'userPaymentOption' => [
                'userPaymentOptionId' => TestCaseHelper::getUPOCreditCardId(),
            ],
            'comment'           => 'some comment',
            'urlDetails'        => SimpleData::getUrlDetails(true),
        ];

        $response = $this->service->payout($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetPayoutStatus ./tests/PayoutTest.php
     */
    public function testGetPayoutStatus()
    {
        $paramsPayout = [
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientRequestId'   => '100',
            'clientUniqueId'    => '12345',
            'amount'            => "9.0",
            'currency'          => SimpleData::getCurrency(),
            'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
            'merchantDetails'   => SimpleData::getMerchantDetails(),
            'userPaymentOption' => [
                'userPaymentOptionId' => TestCaseHelper::getUPOCreditCardId(),
            ],
            'comment'           => 'some comment',
            'urlDetails'        => SimpleData::getUrlDetails(true),
        ];
        $responsePayout = $this->service->payout($paramsPayout);

        $params = [
            'clientRequestId'   => $responsePayout['clientRequestId'],
        ];
        $response = $this->service->getPayoutStatus($params);

        $this->assertEquals('SUCCESS', $response['status']);
    }

}
