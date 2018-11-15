<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\CreditCard;
use SafeCharge\Api\Service\UserPaymentOptions;

class UserPaymentOptionsTest extends \PHPUnit\Framework\TestCase
{
    private $_service;

    /**
     * UserPaymentOptionsTest constructor.
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     */
    public function __construct()
    {
        parent::__construct();

        $this->_service = new UserPaymentOptions(TestCaseHelper::getClient());
    }


    /**
     * @return mixed
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testAddUPOCreditCard()
    {
        $cardData = SimpleData::getCarData();
        $params   = [
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'clientRequestId' => '235',
            'ccCardNumber'    => $cardData['cardNumber'],
            'ccExpMonth'      => $cardData['expirationMonth'],
            'ccExpYear'       => $cardData['expirationYear'],
            'ccNameOnCard'    => $cardData['cardHolderName'],
            'billingAddress'  => SimpleData::getBillingAddress(true),
        ];
        $response = $this->_service->addUPOCreditCard($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertContains('ccToken', $response);
        return $response;

    }

    /**
     * @depends testAddUPOCreditCard
     * @param $addUPOCreditCardResponse
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testAddUPOCreditCardByToken($addUPOCreditCardResponse)
    {
        $cardData = SimpleData::getCarData();
        $params   = [
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'clientRequestId' => '500',
            //'ccCardNumber'    => $cardData['cardNumber'],
            'ccExpMonth'      => $cardData['expirationMonth'],
            'ccExpYear'       => $cardData['expirationYear'],
            'ccNameOnCard'    => $cardData['cardHolderName'],
            'ccToken'         => $addUPOCreditCardResponse['ccToken'],
            'brand'           => $addUPOCreditCardResponse['brand'],
            'uniqueCC'        => $addUPOCreditCardResponse['uniqueCC'],
            'bin'             => $addUPOCreditCardResponse['bin'],
            'last4Digits'     => $addUPOCreditCardResponse['last4Digits'],
            'billingAddress'  => SimpleData::getBillingAddress(true),
        ];
        $response = $this->_service->addUPOCreditCardByToken($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testAddUPOCreditCardByTempToken()
    {
        //Generating ccTempToken
        TestCaseHelper::setSessionToken(null);
        $creditCardService = new CreditCard(TestCaseHelper::getClient());

        $creditCardServiceParams   = [
            'sessionToken'   => TestCaseHelper::getSessionToken(),
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'cardData'       => SimpleData::getCarData(),
            'billingAddress' => SimpleData::getBillingAddress()
        ];
        $creditCardServiceResponse = $creditCardService->cardTokenization($creditCardServiceParams);

        $params   = [
            'sessionToken'    => TestCaseHelper::getSessionToken(),
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'clientRequestId' => '236',
            'ccTempToken'     => $creditCardServiceResponse['ccTempToken'],
            'billingAddress'  => SimpleData::getBillingAddress(),
        ];
        $response = $this->_service->addUPOCreditCardByTempToken($params);
        $this->assertContains('userPaymentOptionId', $response);
    }

    /**
     * @return mixed
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testAddUPOAPM()
    {
        $params   = [
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientRequestId'   => '123',
            'paymentMethodName' => 'apmgw_expresscheckout',
            'apmData'           => [
                'email' => 'user@mail.com'
            ],
            'billingAddress'    => SimpleData::getBillingAddress(true),
        ];
        $response = $this->_service->addUPOAPM($params);
        $this->assertContains('userPaymentOptionId', $response);
        return $response['userPaymentOptionId'];
    }

    /**
     * @depends testAddUPOAPM
     * @param $userPaymentOptionId
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testEditUPOAPM($userPaymentOptionId)
    {
        $params   = [
            'userTokenId'         => TestCaseHelper::getUserTokenId(),
            'clientRequestId'     => '123',
            'userPaymentOptionId' => $userPaymentOptionId,
            'apmData'             => [
                'email' => 'user.updated@mail.com'
            ],
            'billingAddress'      => SimpleData::getBillingAddress(true),
        ];
        $response = $this->_service->editUPOAPM($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @depends testAddUPOCreditCard
     * @param $addUPOCreditCardResponse
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testEditUPOCC($addUPOCreditCardResponse)
    {
        $cardData = SimpleData::getCarData();

        $params   = [
            'userTokenId'         => TestCaseHelper::getUserTokenId(),
            'clientRequestId'     => '4556',
            'userPaymentOptionId' => $addUPOCreditCardResponse['userPaymentOptionId'],
            'ccExpMonth'          => $cardData['expirationMonth'],
            'ccExpYear'           => $cardData['expirationYear'],
            'ccNameOnCard'        => 'Some new updated',
            'billingAddress'      => SimpleData::getBillingAddress(true),
        ];
        $response = $this->_service->editUPOCC($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @depends testAddUPOCreditCardByTempToken
     * @depends testAddUPOAPM
     * @depends testEditUPOAPM
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testGetUserUPOs()
    {
        $params   = [
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'clientRequestId' => '166',
        ];
        $response = $this->_service->getUserUPOs($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertContains('paymentMethods', $response);
        return $response['paymentMethods'];
    }


    /**
     * @depends testGetUserUPOs
     * @param $paymentMethods
     * @return integer
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testSuspendUPO($paymentMethods)
    {
        $userPaymentOptionId = $paymentMethods[0]['userPaymentOptionId'];
        $params              = [
            'userTokenId'         => TestCaseHelper::getUserTokenId(),
            'clientRequestId'     => '188',
            'userPaymentOptionId' => $userPaymentOptionId,
        ];
        $response            = $this->_service->suspendUPO($params);
        $this->assertEquals('SUCCESS', $response['status']);
        return $userPaymentOptionId;
    }

    /**
     * @depends testSuspendUPO
     * @param $userPaymentOptionId
     * @return integer
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testEnableUPO($userPaymentOptionId)
    {
        $params   = [
            'userTokenId'         => TestCaseHelper::getUserTokenId(),
            'clientRequestId'     => '188',
            'userPaymentOptionId' => $userPaymentOptionId,
        ];
        $response = $this->_service->enableUPO($params);
        $this->assertEquals('SUCCESS', $response['status']);
        return $userPaymentOptionId;
    }


    /**
     * @depends testSuspendUPO
     * @param $userPaymentOptionId
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testDeleteUPO($userPaymentOptionId)
    {
        $params   = [
            'userTokenId'         => TestCaseHelper::getUserTokenId(),
            'clientRequestId'     => '188',
            'userPaymentOptionId' => $userPaymentOptionId,
        ];
        $response = $this->_service->deleteUPO($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }


}
