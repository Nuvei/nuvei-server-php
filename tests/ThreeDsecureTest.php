<?php

namespace Nuvei\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Nuvei\Api\Exception\ConfigurationException;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\Payments\ThreeDsecure;

class ThreeDsecureTest extends TestCase
{
    private $service;

    /**
     * ThreeDsecureTest constructor.
     * @throws ConfigurationException
     */
    public function __construct()
    {
        parent::__construct();

        $this->service = new ThreeDsecure(TestCaseHelper::getClient());
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testDynamic3d ./tests/ThreeDsecureTest.php
     */
    public function testDynamic3d()
    {
        TestCaseHelper::setSessionToken(null);

        //TestCaseHelper::setSessionToken(null);
        $params = $this->getExampleData();
        $params['cardData'] = SimpleData::getCardData('375510288656924');
        $transactionData = TestCaseHelper::createAndReturnTransaction(true);
        $params['relatedTransactionId'] = $transactionData['transactionId'];

        $response = $this->service->dynamic3D($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @depends testDynamic3d
     * @param $orderId
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testPayment3d ./tests/ThreeDsecureTest.php
     */
    public function testPayment3d()
    {
        TestCaseHelper::setSessionToken(null);

        $params = $this->getExampleData();
        unset($params['isDynamic3D']);
        $params['orderId']           = TestCaseHelper::openOrderAndReturnOrderId();
        $params['isPartialApproval'] = "0";
        $params['paResponse']        = "";
        $params['transactionType']   = "Sale";
        $params['cardData']          = SimpleData::getCardData();
        $params['paymentMethod']     = 'cc_card';
        $response                    = $this->service->payment3D($params);
        $this->assertContains('orderId', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException

     * @run ./vendor/phpunit/phpunit/phpunit --filter testDynamic3dWithRebillingAndMpi ./tests/ThreeDsecureTest.php
     */
    public function testDynamic3dWithRebillingAndMpi()
    {
        TestCaseHelper::setSessionToken(null);

        $params = $this->getExampleData();

        unset($params['cardData']);

        $params['userTokenId']   = TestCaseHelper::getUserTokenId();
        $params['dynamic3DMode'] = 'OFF';
        $params['isDynamic3D']   = 1;
        $params['isRebilling']   = 1;
        $params['externalMpi']   = [
            "isExternalMpi" => "1",
            "eci"           => "2",
            "cavv"          => "hlk1ABWfdzGVCAAAAABpBBMAAAA=",
            "xid"           => "MDAwMDAwMDAwMDEwMDIxMjg0OTE="
        ];

        $params['userPaymentOption'] = [
            'userPaymentOptionId' => TestCaseHelper::getUPOCreditCardId(),
            'CVV'                 => '234'
        ];

        $transactionData = TestCaseHelper::createAndReturnTransaction(true);
        $params['relatedTransactionId'] = $transactionData['transactionId'];

        $response = $this->service->dynamic3D($params);
        $this->assertContains('orderId', $response);
        $this->assertContains('paRequest', $response);
        return $response['orderId'];
    }


    /**
     * @return array
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public function getExampleData()
    {
        $params = [
            'sessionToken'      => TestCaseHelper::getSessionToken(),
            // "orderId"           => "",
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientUniqueId'    => '12345',
            'clientRequestId'   => '1484759782197',
            'isDynamic3D'       => '0',
            'currency'          => SimpleData::getCurrency(),
            'amount'            => "50",
            'amountDetails'     => SimpleData::getAmountDetails(),
            'items'             => [
                [
                    "id"       => "1",
                    "name"     => "name",
                    "price"    => "50",
                    "quantity" => "1"
                ]
            ],
            'deviceDetails'     => SimpleData::getDeviceDetails(),
            'userDetails'       => SimpleData::getUserDetails(),
            'shippingAddress'   => SimpleData::getShippingAddress(),
            'billingAddress'    => SimpleData::getBillingAddress(),
            'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
            'merchantDetails'   => SimpleData::getMerchantDetails(),
            'addendums'         => SimpleData::getAddEndUms(),
            'cardData'          => [],
//            'userPaymentOption' => [
//                'userPaymentOptionId' => TestCaseHelper::getUPOCreditCardId(),
//                'CVV'                 => '234'
//            ],
            'urlDetails'        => SimpleData::getUrlDetails(true)
        ];

        return $params;
    }
}
