<?php

namespace Nuvei\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\Payments\AlternativePaymentMethod;

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
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetMerchantPaymentMethods ./tests/AlternativePaymentMethodTest.php
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
