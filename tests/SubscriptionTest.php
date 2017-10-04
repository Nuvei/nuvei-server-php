<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\Subscription;

class SubscriptionTest extends \PHPUnit_Framework_TestCase
{
    private $_service;

    public function __construct()
    {
        $this->_service = new Subscription(TestCaseHelper::getClient());
    }

    public function testGetSubscriptionPlans()
    {
        $params = [
            'clientRequestId' => '12345'
        ];

        $response = $this->_service->getSubscriptionPlans($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertContains('subscriptionPlans', $response);
        return $response['subscriptionPlans'][0]['subscriptionPlanId'];
    }

    /**
     * @depends testGetSubscriptionPlans
     * @param $subscriptionPlans
     * @return
     */
    public function testCreateSubscription($subscriptionPlans)
    {
        $params = [
            'userTokenId'        => TestCaseHelper::getUserTokenId(),
            'clientRequestId'    => '12345',
            'subscriptionPlanId' => $subscriptionPlans,
            'dynamicDescriptor'  => SimpleData::getDynamicDescriptor(),
            'userDetails'        => SimpleData::getUserDetails(),
            'deviceDetails'      => SimpleData::getDeviceDetails(),
            'merchantDetails'    => SimpleData::getMerchantDetails(),
            'urlDetails'         => SimpleData::getUrlDetails(true),
            'cardData'           => SimpleData::getCarData(),
//            'userPaymentOption' => SimpleData::getUserPaymentOption(),
            'billingAddress'     => SimpleData::getBillingAddress(),
        ];

        $response = $this->_service->createSubscription($params);
        $this->assertEquals('SUCCESS', $response['status']);
        return $response['subscriptionId'];
    }

    /**
     * @depends testCreateSubscription
     * @param $subscriptionId
     */
    public function testCancelSubscription($subscriptionId)
    {
        $params = [
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'clientRequestId' => '12345',
            'subscriptionId'  => $subscriptionId,
        ];

        $response = $this->_service->cancelSubscription($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @depends testCreateSubscription
     * @param $subscriptionId
     */
    public function testGetSubscriptionList($subscriptionId)
    {
        $params = [
            'userTokenId'        => TestCaseHelper::getUserTokenId(),
            'clientRequestId'    => '12345',
            'subscriptionStatus' => 'ACTIVE',
            'subscriptionId'     => $subscriptionId,
            'firstResult'        => '',
            'maxResults'         => '100',
        ];

        $response = $this->_service->getSubscriptionList($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertContains('subscriptions', $response);
        $this->assertContains('totalCount', $response);
    }


}
