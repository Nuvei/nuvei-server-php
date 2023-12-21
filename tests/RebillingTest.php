<?php

namespace Nuvei\Tests;

use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\Payments\Subscription;
use Nuvei\Api\Service\Rebilling;
use PHPUnit\Framework\TestCase;

class RebillingTest extends TestCase
{
    private $rebillingService;
    private $subscriptionService;

    public function __construct()
    {
        parent::__construct();

        $this->rebillingService = new Rebilling(TestCaseHelper::getclient());
        $this->subscriptionService = new Subscription(TestCaseHelper::getclient());
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetPlanList ./tests/RebillingTest.php
     */
    public function testGetPlanList()
    {
        $response = $this->rebillingService->getPlansList();
        $this->assertArrayHasKey('total', $response);
        $this->assertArrayHasKey('plans', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testCreateImmediatePlan ./tests/RebillingTest.php
     */
    public function testCreateImmediatePlan()
    {
        
        $response = $this->createImmediatePlan();
        $this->assertArrayHasKey('planId', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testCreatePostponedPlan ./tests/RebillingTest.php
     */
    public function testCreatePostponedPlan()
    {
        
        $response = $this->createPostponedPlan();
        $this->assertArrayHasKey('planId', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testEditPlan ./tests/RebillingTest.php
     */
    public function testEditPlan()
    {
        $existingPlan = $this->createImmediatePlan();
        $params = [
            'planId' => $existingPlan['planId'],
            'name' => $existingPlan['name'] . ' Updated',
            'planStatus' => 'INACTIVE',
            'currency' => SimpleData::getCurrency(),
            'initialAmount' => '13.67',
            'recurringAmount' => '3.24',
        ];

        $response = $this->rebillingService->editPlan($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testCreateSubscription ./tests/RebillingTest.php
     */
    public function testCreateSubscription()
    {
        $response = $this->createSubscription();

        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testEditSubscription ./tests/RebillingTest.php
     */
    public function testEditSubscription()
    {
        $existingSubscription = $this->createSubscription();
        $params = [
            'subscriptionId' => $existingSubscription['subscriptionId']
        ];
        $response = $this->subscriptionService->editSubscription($params);

        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetSubscriptionsList ./tests/RebillingTest.php
     */
    public function testGetSubscriptionsList()
    {
        $response = $this->subscriptionService->getSubscriptionsList([]);
        $this->assertArrayHasKey('total', $response);
        $this->assertArrayHasKey('subscriptions', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetExistingSubscriptionsList ./tests/RebillingTest.php
     */
    public function testGetExistingSubscriptionsList()
    {
        $existingSubscription = $this->createSubscription();
        $params = [
            'subscriptionIds' => [$existingSubscription['subscriptionId']]
        ];
        $response = $this->subscriptionService->getSubscriptionsList($params);
        $this->assertArrayHasKey('total', $response);
        $this->assertArrayHasKey('subscriptions', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetExistingSubscriptionsListForPlanIds ./tests/RebillingTest.php
     */
    public function testGetExistingSubscriptionsListForPlanIds()
    {
        $existingPlan = $this->createImmediatePlan();
        $this->createSubscription($existingPlan);
        $params = [
            'planIds' => [$existingPlan['planId']]
        ];
        $response = $this->subscriptionService->getSubscriptionsList($params);
        $this->assertArrayHasKey('total', $response);
        $this->assertArrayHasKey('subscriptions', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testCancelSubscription ./tests/RebillingTest.php
     */
    public function testCancelSubscription()
    {
        $existingSubscription = $this->createSubscription();
        $params = [
            'subscriptionId' => $existingSubscription['subscriptionId']
        ];
        $response = $this->subscriptionService->cancelSubscription($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    private function createImmediatePlan()
    {
        $planName = TestCaseHelper::getPlanName(); 
        $params = [
            'name' => $planName,
            'description' => $planName . ' Description',
            'planStatus' => 'ACTIVE',
            'currency' => SimpleData::getCurrency(),
            'initialAmount' => '15.67',
            'recurringAmount' => '4.24',
            'startAfter' => [
                'day' => '0',
                'month' => '0',
                'year' => '0',
            ],
            'recurringPeriod' => [
                'day' => '1',
                'month' => '0',
                'year' => '0',
            ],
            'endAfter' => [
                'day' => '6',
                'month' => '7',
                'year' => '8',
            ],
        ];

        $response = $this->rebillingService->createPlan($params);
        $response['name'] = $planName;
        return $response;
    }

    private function createPostponedPlan()
    {
        $planName = TestCaseHelper::getPlanName(); 
        $params = [
            'name' => $planName,
            'description' => $planName . ' Description',
            'planStatus' => 'ACTIVE',
            'currency' => SimpleData::getCurrency(),
            'initialAmount' => '15.67',
            'recurringAmount' => '4.24',
            'startAfter' => [
                'day' => '3',
                'month' => '2',
                'year' => '1',
            ],
            'recurringPeriod' => [
                'day' => '1',
                'month' => '0',
                'year' => '0',
            ],
            'endAfter' => [
                'day' => '6',
                'month' => '7',
                'year' => '8',
            ],
        ];

        $response = $this->rebillingService->createPlan($params);
        $response['name'] = $planName;
        return $response;
    }

    private function createSubscription($existingPlan = false)
    {
        if(!$existingPlan) {
            $existingPlan = $this->createImmediatePlan();
        }
        $params = [
            'planId' => $existingPlan['planId'],
            'userTokenId' => TestCaseHelper::getUserTokenId(),
            'userPaymentOptionId' => TestCaseHelper::getUPOCreditCardId(),
            'endAfter' => [
                'day' => '6',
                'month' => '7',
                'year' => '8',
            ],
        ];

        $return = $this->subscriptionService->createSubscription($params);
        $return['plan'] = $existingPlan;
        return $return;
    }
}