<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\CreditCard;


class CreditCardTest extends \PHPUnit_Framework_TestCase
{
    private $_service;

    public function __construct()
    {
        $this->_service = new CreditCard(TestCaseHelper::getClient());
    }

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
     */
    public function testPaymentCCWithCcTempToken($ccTempToken)
    {
        /**
         * The tempToken should be send with the sessionKey which It was generated.
         */
        $params             = $this->getExampleData();
        $params['cardData'] = SimpleData::getCarData($ccTempToken);

        $response = $this->_service->paymentCC($params);
        $this->assertContains('orderId', $response);
    }

    public function testPaymentCCWithCard()
    {
        TestCaseHelper::setSessionToken(null);
        $params             = $this->getExampleData();
        $params['cardData'] = SimpleData::getCarData();

        $response = $this->_service->paymentCC($params);
        $this->assertContains('orderId', $response);
    }

    public function testPaymentCCWithOrderIdAndCard()
    {
        //TestCaseHelper::openOrderAndReturnOrderId() will create a new sessionToken
        $orderId = TestCaseHelper::openOrderAndReturnOrderId();
        $params             = $this->getExampleData();
        $params['cardData'] = SimpleData::getCarData();
        $params['orderId']  = $orderId;

        $response = $this->_service->paymentCC($params);
        $this->assertContains('orderId', $response);
    }

    public function getExampleData()
    {
        $params = [
            'sessionToken'      => TestCaseHelper::getSessionToken(),
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientRequestId'   => '',
            'transactionType'   => 'Auth',
            'isRebilling'       => '0',
            'isPartialApproval' => '0',
            'currency'          => 'EUR',
            'amount'            => "10",
            'amountDetails'     => SimpleData::getAmountDetails(),
            'items'             => SimpleData::getItems(),
            'deviceDetails'     => SimpleData::getDeviceDetails(),
            'userDetails'       => SimpleData::getUserDetails(),
            'shippingAddress'   => SimpleData::getShippingAddress(),
            'billingAddress'    => SimpleData::getBillingAddress(),
            'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
            'merchantDetails'   => SimpleData::getMerchantDetails(),
            'addendums'         => [
                'localPayment' => [
                    'nationalId'            => '012345678',
                    'debitType'             => '2',
                    'firstInstallment'      => '4',
                    'periodicalInstallment' => '3',
                    'numberOfInstallments'  => '3'
                ]
            ],
            'cardData'          => [],
//            'userPaymentOption' => SimpleData::getUserPaymentOption(),
            'urlDetails'        => SimpleData::getUrlDetails()
        ];

        return $params;
    }

}
