<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\Payments\CreditCard;
use SafeCharge\Api\Service\UserPaymentOptions;

class UserPaymentOptionsTest extends \PHPUnit_Framework_TestCase
{
    private $_service;

    public function __construct()
    {
        $this->_service = new UserPaymentOptions(TestCaseHelper::getClient());
    }


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
