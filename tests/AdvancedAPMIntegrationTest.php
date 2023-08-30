<?php

namespace Nuvei\Tests;

use Nuvei\Api\Service\PaymentService;
use PHPUnit\Framework\TestCase;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\AdvancedAPMIntegration;

class advancedAPMIntegrationTest extends TestCase
{
    private $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new AdvancedAPMIntegration(TestCaseHelper::getClient());
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testAddBankAccount ./tests/AdvancedAPMIntegrationTest.php
     */
    public function testAddBankAccount()
    {

        $userTokenId = sha1(time());
        $bankData = SimpleData::getBankData();

        $params = [
            'sessionToken'      => TestCaseHelper::getSessionToken(),
            'clientUniqueId'    => '12345',
            "deviceDetails" => SimpleData::getDeviceDetails(),
            'clientRequestId'   => '100',
            'userId' => '6303323',
            'bankAccount' => $bankData['bankAccount'],
            'userTokenId'     => $userTokenId,
            'paymentOption' =>[
                'alternativePaymentMethod' => [
                    'paymentMethod' => 'apmgw_VIP_Preferred'
                ],
            ],
            'userDetails' => SimpleData::getUserDetails(),
            'documentDetails' => SimpleData::getDocumentDetails()
        ];
        $params['userDetails']['identification'] = SimpleData::getUserDetailsIdentification();

        $response = $this->service->addBankAccount($params);
        //$this->assertArrayHasKey('userId', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testEnrollAccount ./tests/AdvancedAPMIntegrationTest.php
     */
    public function testEnrollAccount()
    {

        $userTokenId = sha1(time());
        $bankData = SimpleData::getBankData();

        $params = [
            'sessionToken'      => TestCaseHelper::getSessionToken(),
            'clientUniqueId'    => '12345',
            "deviceDetails" => SimpleData::getDeviceDetails(),
            'clientRequestId'   => '100',
            'userId' => '6303323',
            'bankAccount' => $bankData['bankAccount'],
            'userTokenId'     => $userTokenId,
            'paymentOption' =>[
                'alternativePaymentMethod' => [
                    'paymentMethod' => 'apmgw_VIP_Preferred'
                ],
            ],
            'userDetails' => SimpleData::getUserDetails(),
            'documentDetails' => SimpleData::getDocumentDetails(),
        ];
        $params['userDetails']['identification'] = SimpleData::getUserDetailsIdentification();

        $response = $this->service->enrollAccount($params);
        //$this->assertArrayHasKey('userId', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }
}
