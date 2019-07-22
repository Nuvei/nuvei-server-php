<?php

namespace SafeCharge\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use SafeCharge\Api\Exception\ConnectionException;
use SafeCharge\Api\Exception\ResponseException;
use SafeCharge\Api\Exception\ValidationException;
use SafeCharge\Api\Service\Payments\AlternativePaymentMethod;

class AlternativePaymentMethodTest extends TestCase
{
    private $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new AlternativePaymentMethod(TestCaseHelper::getClient());


    }

    /**
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
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

        $response = $this->service->getMerchantPaymentMethods($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
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
        $response = $this->service->paymentAPM($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }
}
