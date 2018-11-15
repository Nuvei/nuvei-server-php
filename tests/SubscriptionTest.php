<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\Subscription;

class SubscriptionTest extends \PHPUnit\Framework\TestCase
{
    private $_service;

    public function __construct()
    {
        parent::__construct();

        $this->_service = new Subscription(TestCaseHelper::getClient());
    }

    /**
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
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
     * @param $subscriptionPlanId
     * @return mixed
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testCreateSubscription($subscriptionPlanId)
    {
        $params = [
            'userTokenId'        => TestCaseHelper::getUserTokenId(),
            'clientRequestId'    => '12345',
            'subscriptionPlanId' => $subscriptionPlanId,
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
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
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
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testGetSubscriptionsList()
    {
        $params = [
            'userTokenId'        => TestCaseHelper::getUserTokenId(),
            'clientRequestId'    => '123456aaa',
            'subscriptionStatus' => 'ACTIVE',
            'firstResult'        => '0',
            'maxResults'         => '5',
        ];

        $response = $this->_service->getSubscriptionsList($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertContains('subscriptions', $response);
        $this->assertContains('totalCount', $response);
    }


}
