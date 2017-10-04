<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\ThreeDsecure;

class ThreeDsecureTest extends \PHPUnit_Framework_TestCase
{
    private $_service;

    public function __construct()
    {
        $this->_service = new ThreeDsecure(TestCaseHelper::getClient());
    }

    public function testDynamic3d()
    {
        TestCaseHelper::setSessionToken(null);
        $params             = $this->getExampleData();
        $params['cardData'] = SimpleData::getCarData();

        $response = $this->_service->dynamic3D($params);
        $this->assertContains('orderId', $response);
        $this->assertContains('paRequest', $response);
        return $response['orderId'];
    }

    /**
     * @depends testDynamic3d
     * @param $orderId
     */
    public function testPayment3d($orderId)
    {
        $params = $this->getExampleData();
        unset($params['isDynamic3D']);
        $params['orderId']           = $orderId;
        $params['isPartialApproval'] = "0";
        $params['paResponse']        = "";
        $params['transactionType']   = "Sale";
        $params['cardData']          = SimpleData::getCarData();
        $response                    = $this->_service->payment3D($params);
        $this->assertContains('orderId', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    public function getExampleData()
    {
        $params = [
            'sessionToken'      => TestCaseHelper::getSessionToken(),
            // "orderId"           => "",
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientUniqueId'    => '12345',
            'clientRequestId'   => '1484759782197',
            'isDynamic3D'       => '0',
            'currency'          => 'EUR',
            'amount'            => "5000",
            'amountDetails'     => SimpleData::getAmountDetails(),
            'items'             => [
                [
                    "id"       => "1",
                    "name"     => "name",
                    "price"    => "5000",
                    "quantity" => "1"
                ]
            ],
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
//        'userPaymentOption' => SimpleData::getUserPaymentOption(),
            'urlDetails'        => SimpleData::getUrlDetails(true)
        ];

        return $params;
    }
}
