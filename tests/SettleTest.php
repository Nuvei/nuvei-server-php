<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\Settle;

class SettleTest extends \PHPUnit_Framework_TestCase
{
    private $_service;

    public function __construct()
    {
        $this->_service = new Settle(TestCaseHelper::getClient());
    }

    public function testSettleTransaction()
    {

        $transactionData = TestCaseHelper::createAndReturnTransaction(10, true);

        $params = [
            'clientRequestId'         => '100',
            'clientUniqueId'          => '12345',
            'amount'                  => "9.0",
            'currency'                => SimpleData::getCurrency(),
            'relatedTransactionId'    => $transactionData['transactionId'],
            'authCode'                => $transactionData['authCode'],
            'descriptorMerchantName'  => 'Name',
            'descriptorMerchantPhone' => '+4412378',
            'comment'                 => 'some comment',
            'urlDetails'              => SimpleData::getUrlDetails(true),
        ];

        $response = $this->_service->settleTransaction($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

}
