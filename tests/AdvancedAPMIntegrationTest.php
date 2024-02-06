<?php

namespace Nuvei\Tests;

use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\AdvancedAPMIntegration;
use PHPUnit\Framework\TestCase;

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
     * @run ./vendor/phpunit/phpunit/phpunit --filter testEnrollAccount ./tests/AdvancedAPMIntegrationTest.php
     */
    public function testEnrollAccount()
    {

        $response = $this->enrollAccount();
        $this->assertEquals('SUCCESS', $response['status']);
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
        $this->enrollAccount();

        $userTokenId = sha1(time());
        $bankData    = SimpleData::getBankData();

        $params = [
            'sessionToken'    => TestCaseHelper::getSessionToken(),
            'clientUniqueId'  => '12345',
            "deviceDetails"   => SimpleData::getDeviceDetails(),
            'clientRequestId' => '100',
            'userId'          => '6303323',
            'bankAccount'     => $bankData['bankAccount'],
            'userTokenId'     => $userTokenId,
            'paymentOption'   => [
                'alternativePaymentMethod' => [
                    'paymentMethod' => 'apmgw_VIP_Preferred',
                ],
            ],
            'userDetails'     => SimpleData::getUserDetails(),
            'documentDetails' => SimpleData::getDocumentDetails(),
        ];
        $params['userDetails']['identification'] = SimpleData::getUserDetailsIdentification();

        $response = $this->service->addBankAccount($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testFundAccount ./tests/AdvancedAPMIntegrationTest.php
     */
    public function testFundAccount()
    {
        $params = [
            'sessionToken'    => TestCaseHelper::getSessionToken(),
            'clientUniqueId'  => '12345',
            'clientRequestId' => '100',
            'paymentOption'   => [
                'alternativePaymentMethod' => [
                    'paymentMethod' => 'apmgw_PlayPlus',
                ],
            ],
            'userId'          => '6303323',
            "deviceDetails"   => SimpleData::getDeviceDetails(),
            'userDetails'     => SimpleData::getUserDetails(),
            'urlDetails'      => SimpleData::getUrlDetails(),
        ];
        $params['userDetails']['identification'] = SimpleData::getUserDetailsIdentification();

        $response = $this->service->fundAccount($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetAccountDetails ./tests/AdvancedAPMIntegrationTest.php
     */
    public function testGetAccountDetails()
    {
        $this->enrollAccount();
        $params = [
            'sessionToken'    => TestCaseHelper::getSessionToken(),
            'clientUniqueId'  => '12345',
            'clientRequestId' => '100',
            'paymentOption'   => [
                'alternativePaymentMethod' => [
                    'paymentMethod' => 'apmgw_VIP_Preferred',
                ],
            ],
            'userId'          => sha1(time()),
            "deviceDetails"   => SimpleData::getDeviceDetails(),
            'userDetails'     => SimpleData::getUserDetails(),
        ];
        $params['userDetails']['identification'] = SimpleData::getUserDetailsIdentification();

        $response = $this->service->getAccountDetails($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter getDocumentUrl ./tests/AdvancedAPMIntegrationTest.php
     */
    public function testGetDocumentUrl()
    {
        $params = [
            'sessionToken'    => TestCaseHelper::getSessionToken(),
            'clientUniqueId'  => '12345',
            'clientRequestId' => '100',
            'paymentOption'   => [
                'alternativePaymentMethod' => [
                    'paymentMethod' => 'apmgw_PlayPlus',
                ],
            ],
            'documentType' => TestCaseHelper::getDocumentType(),
        ];
        $params['userDetails']['identification'] = SimpleData::getUserDetailsIdentification();

        $response = $this->service->getDocumentUrl($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }
    
    private function enrollAccount()
    {
        $userTokenId = sha1(time());
        $bankData    = SimpleData::getBankData();

        $params = [
            'sessionToken'    => TestCaseHelper::getSessionToken(),
            'clientUniqueId'  => '12345',
            "deviceDetails"   => SimpleData::getDeviceDetails(),
            'clientRequestId' => '100',
            'userId'          => '6303323',
            'bankAccount'     => $bankData['bankAccount'],
            'userTokenId'     => $userTokenId,
            'paymentOption'   => [
                'alternativePaymentMethod' => [
                    'paymentMethod' => 'apmgw_VIP_Preferred',
                ],
            ],
            'userDetails'     => SimpleData::getUserDetails(),
            'documentDetails' => SimpleData::getDocumentDetails(),
        ];
        $params['userDetails']['identification'] = SimpleData::getUserDetailsIdentification();

        return $this->service->enrollAccount($params);
    }
}
