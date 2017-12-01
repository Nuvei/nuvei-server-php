<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\Refund;

class RefundTest extends \PHPUnit_Framework_TestCase
{
    private $_service;

    public function __construct()
    {
        $this->_service = new Refund(TestCaseHelper::getClient());
    }

    /**
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
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

        $response = $this->_service->refundTransaction($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

}
