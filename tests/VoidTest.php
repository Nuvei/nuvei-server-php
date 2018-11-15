<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\Void;

class VoidTest extends \PHPUnit_Framework_TestCase
{
    private $_service;

    /**
     * VoidTest constructor.
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     */
    public function __construct()
    {
        $this->_service = new Void(TestCaseHelper::getClient());
    }

    /**
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testVoidTransaction()
    {

        $transactionData = TestCaseHelper::createAndReturnTransaction(false);

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

        $response = $this->_service->voidTransaction($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

}
