<?php

namespace Nuvei\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Nuvei\Api\Exception\ConfigurationException;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\PaymentService;

/**
 * Class PaymentServiceTest
 * @package Nuvei\Tests
 */
class PaymentServiceTest extends TestCase
{
    /**
     * @var PaymentService
     */
    private $service;

    /**
     * PaymentServiceTest constructor.
     * @throws ConfigurationException
     */
    public function __construct()
    {
        parent::__construct();

        $this->service = new PaymentService(TestCaseHelper::getClient());
    }

    /**
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testCreatePayment ./tests/PaymentServiceTest.php
     */
    public function testCreatePayment()
    {
        $response = $this->service->createPayment([
            'currency'       => SimpleData::getCurrency(),
            'amount'         => SimpleData::getAmount(),
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'paymentOption'  => [
                'card' => SimpleData::getCardData()
            ],
            'billingAddress' => SimpleData::getBillingAddress(),
            'deviceDetails'  => SimpleData::getDeviceDetails(),
            //'currencyConversion'  => SimpleData::getCurrencyConversion(),
        ]);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testCreatePaymentCvvNotUsed ./tests/PaymentServiceTest.php
     */
    public function testCreatePaymentCvvNotUsed()
    {
        try {
            $this->service->createPayment([
                'currency'       => SimpleData::getCurrency(),
                'amount'         => SimpleData::getAmount(),
                'userTokenId'    => TestCaseHelper::getUserTokenId(),
                'paymentOption'  => [
                    'card' => SimpleData::getCardData()
                ],
                'billingAddress' => SimpleData::getBillingAddress(),
                'deviceDetails'  => SimpleData::getDeviceDetails(),
                //'currencyConversion'  => SimpleData::getCurrencyConversion(),
                'cvvNotUsed'     => 'invalid',
            ]);
        } catch (ResponseException $e) {
            $this->assertEquals('cvvNotUsed size must be between 0 and 5', $e->getMessage());
        }
    }

    /**
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testInitPayment ./tests/PaymentServiceTest.php
     */
    public function testInitPayment()
    {
        $response = $this->service->initPayment([
            'clientUniqueId'    => '12345',
            'currency'       => SimpleData::getCurrency(),
            'amount'         => SimpleData::getAmount(),
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'paymentOption'  => [
                'card' => SimpleData::getCardData()
            ],
            'billingAddress' => SimpleData::getBillingAddress(),
            'deviceDetails'  => SimpleData::getDeviceDetails()
        ]);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return mixed
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testOpenOrder ./tests/PaymentServiceTest.php
     */
    public function testOpenOrder()
    {
        $params = [
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientUniqueId'    => '',
            'clientRequestId'   => '',
            'currency'          => SimpleData::getCurrency(),
            'amount'            => SimpleData::getAmount(),
            'amountDetails'     => SimpleData::getAmountDetails(),
            'items'             => SimpleData::getItems(),
            'deviceDetails'     => SimpleData::getDeviceDetails(),
            'userDetails'       => SimpleData::getUserDetails(),
            'shippingAddress'   => SimpleData::getShippingAddress(),
            'billingAddress'    => SimpleData::getBillingAddress(),
            'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
            'merchantDetails'   => SimpleData::getMerchantDetails(),
            'addendums'         => SimpleData::getAddEndUms(),
            'currencyConversion'=> SimpleData::getCurrencyConversion(),
        ];

        $response = $this->service->openOrder($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testSettleTransaction ./tests/PaymentServiceTest.php
     */
    public function testSettleTransaction()
    {

        $transactionData = TestCaseHelper::createAndReturnTransaction(true);

        $dynamicDescriptor = SimpleData::getDynamicDescriptor();

        $params = [
            'clientRequestId'         => '100',
            'clientUniqueId'          => '12345',
            'amount'                  => "9.0",
            'currency'                => SimpleData::getCurrency(),
            'relatedTransactionId'    => $transactionData['transactionId'],
            'authCode'                => $transactionData['authCode'],
            'descriptorMerchantName'  => $dynamicDescriptor['merchantName'],
            'descriptorMerchantPhone' => $dynamicDescriptor['merchantPhone'],
            'comment'                 => 'some comment',
            'urlDetails'              => SimpleData::getUrlDetails(true),
        ];

        $response = $this->service->settleTransaction($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }


    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testRefundTransaction ./tests/PaymentServiceTest.php
     */
    public function testRefundTransaction()
    {
        $transactionData = TestCaseHelper::createAndReturnTransaction(false);

        $params = [
            'clientRequestId'      => '100',
            'clientUniqueId'       => '12345',
            'amount'               => SimpleData::getAmount(),
            'currency'             => SimpleData::getCurrency(),
            'relatedTransactionId' => $transactionData['transactionId'],
            'authCode'             => $transactionData['authCode'],
            'comment'              => 'some comment',
            'urlDetails'           => SimpleData::getUrlDetails(true),
        ];

        $response = $this->service->refundTransaction($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testVoidTransaction ./tests/PaymentServiceTest.php
     */
    public function testVoidTransaction()
    {
        $transactionData = TestCaseHelper::createAndReturnTransaction(false);

        $dynamicDescriptor = SimpleData::getDynamicDescriptor();

        $params = [
            'clientRequestId'         => '100',
            'clientUniqueId'          => '12345',
            'amount'                  => $transactionData['partialApprovalDetails']['amountInfo']['processedAmount'],
            'currency'                => SimpleData::getCurrency(),
            'relatedTransactionId'    => $transactionData['transactionId'],
            'authCode'                => $transactionData['authCode'],
            'descriptorMerchantName'  => $dynamicDescriptor['merchantName'],
            'descriptorMerchantPhone' => $dynamicDescriptor['merchantPhone'],
            'comment'                 => 'some comment',
            'urlDetails'              => SimpleData::getUrlDetails(true),
        ];

        $response = $this->service->voidTransaction($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetPaymentStatus ./tests/PaymentServiceTest.php
     */
    public function testGetPaymentStatus()
    {
        $createPayment = $this->service->createPayment([
            'currency'       => SimpleData::getCurrency(),
            'amount'         => SimpleData::getAmount(),
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'paymentOption'  => [
                'card' => SimpleData::getCardData()
            ],
            'billingAddress' => SimpleData::getBillingAddress(),
            'deviceDetails'  => SimpleData::getDeviceDetails()
        ]);

        $params = [
            'sessionToken' => $createPayment['sessionToken']
        ];

        $response = $this->service->getPaymentStatus($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testAccountCapture ./tests/PaymentServiceTest.php
     */
    public function testAccountCapture()
    {
        $createPayment = $this->service->createPayment([
            'currency'       => SimpleData::getCurrency(),
            'amount'         => SimpleData::getAmount(),
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'paymentOption'  => [
                'card' => SimpleData::getCardData()
            ],
            'billingAddress' => SimpleData::getBillingAddress(),
            'deviceDetails'  => SimpleData::getDeviceDetails()
        ]);

        $params = [
            'sessionToken'  => $createPayment['sessionToken'],
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'paymentMethod'     => 'apmgw_PayRetailers_Payouts',
            'currencyCode'      => 'USD',
            'countryCode'       => 'US',
            'amount'            => SimpleData::getAmount(),
            'languageCode'      => 'en',
            //'notificationUrl'   =>  ''
        ];

        $response = $this->service->accountCapture($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return mixed
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetCardDetails ./tests/PaymentServiceTest.php
     */
    public function testGetCardDetails()
    {
        $params = [
            'cardNumber' => SimpleData::getCardNumber()
        ];

        $response = $this->service->getCardDetails($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return mixed
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetMcpRates ./tests/PaymentServiceTest.php
     */
    public function testGetMcpRates()
    {
        $params = [
            'fromCurrency' => SimpleData::getCurrency()
        ];

        $response = $this->service->getMcpRates($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return mixed
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetDccDetails ./tests/PaymentServiceTest.php
     */
    public function testGetDccDetails()
    {
        $params = [
            'clientRequestId'       => '100',
            'clientUniqueId'        => '12345',
            "apm"                   => "apmgw_expresscheckout",
            'amount'                => '10',
            'originalAmount'        => '15',
            'originalCurrency'      => 'GBP',
            'currency'              => 'EUR',
        ];

        $response = $this->service->getDccDetails($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testAuthorize3d ./tests/PaymentServiceTest.php
     */
    public function testAuthorize3d()
    {
        $paramsAuthorize3d = $paramsInitPayment = [
            'clientUniqueId'    => '12345',
            'currency'       => SimpleData::getCurrency(),
            'amount'         => SimpleData::getAmount(151),
            'paymentOption'  => [
                'card' => SimpleData::getCardDataVerify3d()
            ],
            //'threeD'  => SimpleData::getCardThreeD(),
            'billingAddress' => SimpleData::getBillingAddress(),
            'deviceDetails'  => SimpleData::getDeviceDetails(),
        ];

        $paramsInitPayment['userTokenId'] = TestCaseHelper::getUserTokenId();
        $initPaymentResponse = $this->service->initPayment($paramsInitPayment);

        $paramsAuthorize3d['relatedTransactionId'] = $initPaymentResponse['transactionId'];
        $paramsAuthorize3d['sessionToken'] = $initPaymentResponse['sessionToken'];
        $paramsAuthorize3d['paymentOption']['card']['threeD'] = SimpleData::getCardThreeD();

        $authorize3dResponse = $this->service->authorize3d($paramsAuthorize3d);

        $this->assertEquals('SUCCESS', $authorize3dResponse['status']);
    }

    /**
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testVerify3d ./tests/PaymentServiceTest.php
     */
    public function testVerify3d()
    {
        $paramsVerify3d = $paramsAuthorize3d = $paramsInitPayment = [
            'clientUniqueId'    => '12345',
            'currency'       => SimpleData::getCurrency(),
            'amount'         => SimpleData::getAmount(),
            'paymentOption'  => [
                'card' => SimpleData::getCardDataVerify3d(false, false, false)
            ],
            'billingAddress' => SimpleData::getBillingAddress(),
        ];

        $paramsInitPayment['userTokenId'] = TestCaseHelper::getUserTokenId();
        $paramsInitPayment['deviceDetails'] = SimpleData::getDeviceDetails();

        $initPaymentResponse = $this->service->initPayment($paramsInitPayment);

        $paramsAuthorize3d['relatedTransactionId'] = $initPaymentResponse['transactionId'];
        $paramsAuthorize3d['deviceDetails'] = SimpleData::getDeviceDetails();
        $paramsAuthorize3d['sessionToken'] = $initPaymentResponse['sessionToken'];
        $paramsAuthorize3d['paymentOption']['card']['threeD'] = SimpleData::getCardThreeD();

        $authorize3dResponse = $this->service->authorize3d($paramsAuthorize3d);

        $paramsVerify3d['relatedTransactionId'] = $authorize3dResponse['transactionId'];
        $paramsVerify3d['sessionToken'] = $authorize3dResponse['sessionToken'];

        $verify3dResponse = $this->service->verify3d($paramsVerify3d);

        $this->assertEquals('SUCCESS', $verify3dResponse['status']);
    }
}
