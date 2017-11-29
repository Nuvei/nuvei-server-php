<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\OrdersManagement;

class OrdersManagementTest extends \PHPUnit_Framework_TestCase
{

    private $_service;

    public function __construct()
    {
        $this->_service = new OrdersManagement(TestCaseHelper::getClient());
    }

    public function testOpenOrder()
    {
        TestCaseHelper::setSessionToken(null);
        $params = [
            'sessionToken'      => TestCaseHelper::getSessionToken(),
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientUniqueId'    => '',
            'clientRequestId'   => '',
            'currency'          => SimpleData::getCurrency(),
            'amount'            => "10",
            'amountDetails'     => SimpleData::getAmountDetails(),
            'items'             => SimpleData::getItems(),
            'deviceDetails'     => SimpleData::getDeviceDetails(),
            'userDetails'       => SimpleData::getUserDetails(),
            'shippingAddress'   => SimpleData::getShippingAddress(),
            'billingAddress'    => SimpleData::getBillingAddress(),
            'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
            'merchantDetails'   => SimpleData::getMerchantDetails(),
            'addendums'         => SimpleData::getAddEndUms(),
        ];

        $response = $this->_service->openOrder($params);
        $this->assertContains('orderId', $response);
        return $response['orderId'];
    }

    /**
     * @depends testOpenOrder
     * @param $orderId
     */
    public function testUpdateOrder($orderId)
    {
        $params   = [
            'sessionToken'      => TestCaseHelper::getSessionToken(),
            "orderId"           => $orderId,
            'clientUniqueId'    => '',
            'clientRequestId'   => '',
            'currency'          => SimpleData::getCurrency(),
            'amount'            => "10",
            'amountDetails'     => SimpleData::getAmountDetails(),
            'items'             => SimpleData::getItems(),
            'deviceDetails'     => SimpleData::getDeviceDetails(),
            'userDetails'       => SimpleData::getUserDetails(),
            'shippingAddress'   => [
                "firstName" => "some first name - updated",
                "lastName"  => "some last name - updated",
                "cell"      => "",
                "phone"     => "972502457558111",
                "email"     => "someemail@somedomainupdated.com",
                "address"   => "some street - updated",
                "city"      => "some city - updated",
                "zip"       => "",
                "country"   => "US",
                "state"     => "AK"
            ],
            'billingAddress'    => SimpleData::getBillingAddress(),
            'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
            'merchantDetails'   => SimpleData::getMerchantDetails(),
            'addendums'         => SimpleData::getAddEndUms(),
        ];
        $response = $this->_service->updateOrder($params);
        $this->assertContains('orderId', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @depends testOpenOrder
     * @depends testUpdateOrder
     * @param $orderId
     */
    public function testGetOrderDetails($orderId)
    {
        $params   = [
            "sessionToken"    => TestCaseHelper::getSessionToken(),
            "orderId"         => $orderId,
            "clientRequestId" => "1484759782197",
        ];
        $response = $this->_service->getOrderDetails($params);
        $this->assertContains('orderId', $response);
        $this->assertContains('currency', $response);
        $this->assertContains('amount', $response);
        $this->assertContains('amountDetails', $response);
        $this->assertEquals('some first name - updated', $response['shippingAddress']['firstName']);
    }
}
