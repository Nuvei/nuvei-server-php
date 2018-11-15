<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\CreditCard;


class CreditCardTest extends \PHPUnit\Framework\TestCase
{
    private $_service;

    public function __construct()
    {
        parent::__construct();

        $this->_service = new CreditCard(TestCaseHelper::getClient());
    }

    /**
     * @return mixed
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testCardTokenization()
    {
        TestCaseHelper::setSessionToken(null);
        $params   = [
            'sessionToken'   => TestCaseHelper::getSessionToken(),
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'cardData'       => SimpleData::getCarData(),
            'billingAddress' => SimpleData::getBillingAddress()
        ];
        $response = $this->_service->cardTokenization($params);
        $this->assertContains('ccTempToken', $response);
        return $response['ccTempToken'];
    }

    /**
     * @depends testCardTokenization
     * @param $ccTempToken
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testPaymentCCWithCcTempToken($ccTempToken)
    {
        /**
         * The tempToken should be send with the sessionKey which It was generated.
         */
        $params             = $this->getExampleData();
        $params['cardData'] = SimpleData::getCarData(false, $ccTempToken);

        $response = $this->_service->paymentCC($params);
        $this->assertContains('orderId', $response);
    }

    /**
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testPaymentCCWithCard()
    {
        TestCaseHelper::setSessionToken(null);
        $params             = $this->getExampleData();
        $params['cardData'] = SimpleData::getCarData();

        $response = $this->_service->paymentCC($params);
        $this->assertContains('orderId', $response);
    }

    /**
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testPaymentCCWithOrderIdAndCard()
    {
        //TestCaseHelper::openOrderAndReturnOrderId() will create a new sessionToken
        $orderId            = TestCaseHelper::openOrderAndReturnOrderId();
        $params             = $this->getExampleData();
        $params['cardData'] = SimpleData::getCarData();
        $params['orderId']  = $orderId;

        $response = $this->_service->paymentCC($params);
        $this->assertContains('orderId', $response);
    }

    /**
     * @return array
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
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
