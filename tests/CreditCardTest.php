<?php

namespace Nuvei\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\Payments\CreditCard;


class CreditCardTest extends TestCase
{
    private $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new CreditCard(TestCaseHelper::getClient());
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testCardTokenization ./tests/CreditCardTest.php
     */
    public function testCardTokenization()
    {
        TestCaseHelper::setSessionToken(null);
        $params   = [
            'sessionToken'   => TestCaseHelper::getSessionToken(),
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'cardData'       => SimpleData::getCardData(),
            'billingAddress' => SimpleData::getBillingAddress()
        ];
        $response = $this->service->cardTokenization($params);
        $this->assertContains('ccTempToken', $response);
        return $response['ccTempToken'];
    }

    /**
     * @depends testCardTokenization
     *
     * @param $ccTempToken
     *
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public function testPaymentCCWithCcTempToken($ccTempToken)
    {
        /**
         * The tempToken should be send with the sessionKey which It was generated.
         */
        $params             = $this->getExampleData();
        $params['cardData'] = SimpleData::getCardData(false, $ccTempToken);

        $response = $this->service->paymentCC($params);
        $this->assertContains('orderId', $response);
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public function testPaymentCCWithCard()
    {
        TestCaseHelper::setSessionToken(null);
        $params             = $this->getExampleData();
        $params['cardData'] = SimpleData::getCardData();

        $response = $this->service->paymentCC($params);
        $this->assertContains('orderId', $response);
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public function testPaymentCCWithOrderIdAndCard()
    {
        $orderId            = TestCaseHelper::openOrderAndReturnOrderId();
        $params             = $this->getExampleData();
        $params['cardData'] = SimpleData::getCardData();
        $params['orderId']  = $orderId;

        $response = $this->service->paymentCC($params);
        $this->assertContains('orderId', $response);
    }

    /**
     * @return array
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public function getExampleData()
    {
        $params = [
            'sessionToken'      => TestCaseHelper::getSessionToken(),
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientRequestId'   => '',
            'transactionType'   => 'Auth',
            'isRebilling'       => '0',
            'isPartialApproval' => '0',
            'currency'          => SimpleData::getCurrency(),
            'amount'            => SimpleData::getAmount(),
            'amountDetails'     => SimpleData::getAmountDetails(),
            'items'             => SimpleData::getItems(),
            'deviceDetails'     => SimpleData::getDeviceDetails(),
            'userDetails'       => SimpleData::getUserDetails(),
            'shippingAddress'   => SimpleData::getShippingAddress(),
            'billingAddress'    => SimpleData::getBillingAddress(),
            'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
            'merchantDetails'   => SimpleData::getMerchantDetails(),
            'addendums'         => SimpleData::getAddEndUms(),
            'cardData'          => [],
//            'userPaymentOption' => SimpleData::getUserPaymentOption(),
            'urlDetails'        => SimpleData::getUrlDetails()
        ];

        return $params;
    }

}
