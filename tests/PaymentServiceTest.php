<?php

namespace SafeCharge\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use SafeCharge\Api\Exception\ConfigurationException;
use SafeCharge\Api\Exception\ConnectionException;
use SafeCharge\Api\Exception\ResponseException;
use SafeCharge\Api\Exception\ValidationException;
use SafeCharge\Api\Service\PaymentService;

/**
 * Class PaymentServiceTest
 * @package SafeCharge\Tests
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
     */
    public function testCreatePayment()
    {
        $response = $this->service->createPayment([
            'currency'       => SimpleData::getCurrency(),
            'amount'         => SimpleData::getAmount(),
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'paymentOption'  => [
                'card' => SimpleData::getCarData()
            ],
            'billingAddress' => SimpleData::getBillingAddress(),
            'deviceDetails'  => SimpleData::getDeviceDetails()
        ]);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public function testInitPayment()
    {
        $response = $this->service->createPayment([
            'currency'       => SimpleData::getCurrency(),
            'amount'         => SimpleData::getAmount(),
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'paymentOption'  => [
                'card' => SimpleData::getCarData()
            ],
            'billingAddress' => SimpleData::getBillingAddress()
        ]);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return mixed
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
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
        ];

        $response = $this->service->openOrder($params);
        $this->assertContains('orderId', $response);
        return $response['orderId'];
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
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
}
