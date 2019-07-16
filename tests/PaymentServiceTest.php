<?php

namespace SafeCharge\Tests;

use PHPUnit\Framework\TestCase;
use SafeCharge\Api\Service\PaymentService;

class PaymentServiceTest extends TestCase
{
    private $_service;

    /**
     * SettleTest constructor.
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     */
    public function __construct()
    {
        parent::__construct();

        $this->_service = new PaymentService(TestCaseHelper::getClient());
    }

    public function testCreatePayment()
    {
        $response = $this->_service->createPayment([
            'sessionToken'   => TestCaseHelper::getSessionToken(),
            'currency'       => 'EUR',
            'amount'         => '10',
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'paymentOption'  => [
                'card' => [
                    'cardNumber'      => '4012001037141112',
                    'cardHolderName'  => 'some name',
                    'expirationMonth' => '01',
                    'expirationYear'  => '2020',
                    'CVV'             => '122',
                ]
            ],
            'billingAddress' => [
                "firstName" => "some first name",
                "lastName"  => "some last name",
                "address"   => "some street",
                "phone"     => "972502457558",
                "zip"       => "123456",
                "city"      => "some city",
                'country'   => "DE",
                "state"     => "",
                "email"     => "someemail@somedomain.com",
                "county"    => "Anchorage",
            ]
        ]);
        $this->assertEquals('SUCCESS', $response['status']);
    }
}
