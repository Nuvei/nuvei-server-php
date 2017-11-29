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
            'amount'             => "10",
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
