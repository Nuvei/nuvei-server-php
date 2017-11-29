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

    public function testRefundTransaction()
    {


        $transactionData = TestCaseHelper::createAndReturnTransaction(10, false);

        $params = [
            'clientRequestId'      => '100',
            'clientUniqueId'       => '12345',
            'amount'               => 10,
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
