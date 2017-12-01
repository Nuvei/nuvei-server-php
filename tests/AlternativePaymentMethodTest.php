<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\AlternativePaymentMethod;

class AlternativePaymentMethodTest extends \PHPUnit_Framework_TestCase
{
    private $_service;

    public function __construct()
    {
        $this->_service = new AlternativePaymentMethod(TestCaseHelper::getClient());
    }

    /**
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testGetMerchantPaymentMethods()
    {
        $params = [
            'sessionToken'    => TestCaseHelper::getSessionToken(),
            'clientRequestId' => '1484759782197',
            'currencyCode'    => 'GBP',
            'countryCode'     => 'GB',
            'languageCode'    => 'en',
        ];

        $response = $this->_service->getMerchantPaymentMethods($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testPaymentAPM()
    {
        TestCaseHelper::setSessionToken(null);
        $params   = [
            'sessionToken'       => TestCaseHelper::getSessionToken(),
            // "orderId"           => "",
            'userTokenId'        => TestCaseHelper::getUserTokenId(),
            'clientUniqueId'     => '12345',
            'clientRequestId'    => '1484759782197',
            'currency'           => SimpleData::getCurrency(),
            'amount'             => SimpleData::getAmount(),
            'amountDetails'      => SimpleData::getAmountDetails(),
            'items'              => SimpleData::getItems(),
            'deviceDetails'      => SimpleData::getDeviceDetails(),
            'userDetails'        => SimpleData::getUserDetails(),
            'shippingAddress'    => SimpleData::getShippingAddress(),
            'billingAddress'     => SimpleData::getBillingAddress(),
            'dynamicDescriptor'  => SimpleData::getDynamicDescriptor(),
            'merchantDetails'    => SimpleData::getMerchantDetails(),
            // 'addendums'          => SimpleData::getAddendums(),
            'paymentMethod'      => "apmgw_expresscheckout",
            'userAccountDetails' => SimpleData::getUserAccountDetails(),
            //'userPaymentOption'  => SimpleData::getUserPaymentOption(),
            'urlDetails'         => SimpleData::getUrlDetails(false)
        ];
        $response = $this->_service->paymentAPM($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }
}
