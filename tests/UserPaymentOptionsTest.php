<?php

namespace Nuvei\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Nuvei\Api\Exception\ConfigurationException;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\Payments\CreditCard;
use Nuvei\Api\Service\UserPaymentOptions;

class UserPaymentOptionsTest extends TestCase
{
    private $service;

    /**
     * UserPaymentOptionsTest constructor.
     * @throws ConfigurationException
     */
    public function __construct()
    {
        parent::__construct();

        $this->service = new UserPaymentOptions(TestCaseHelper::getClient());
    }


    /**
     * @group upocct
     * @return mixed
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter ::testAddUPOCreditCard$ ./tests/UserPaymentOptionsTest.php
     */
    public function testAddUPOCreditCard()
    {
        $cardData = SimpleData::getCardData();
        $params   = [
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'clientRequestId' => '235',
            'ccCardNumber'    => $cardData['cardNumber'],
            'ccExpMonth'      => $cardData['expirationMonth'],
            'ccExpYear'       => $cardData['expirationYear'],
            'ccNameOnCard'    => $cardData['cardHolderName'],
            'billingAddress'  => SimpleData::getBillingAddress(true),
        ];
        $response = $this->service->addUPOCreditCard($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertArrayHasKey('ccToken', $response);

        return $response;
    }

    /**
     * @group upocct
     * @depends testAddUPOCreditCard
     * @param $addUPOCreditCardResponse
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --verbose --group upocct
     */
    public function testAddUPOCreditCardByToken($addUPOCreditCardResponse)
    {
        $cardData = SimpleData::getCardData();
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
        $response = $this->service->addUPOCreditCardByToken($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter ::testAddUPOCreditCardByTempToken$ ./tests/UserPaymentOptionsTest.php
     */
    public function testAddUPOCreditCardByTempToken()
    {
        //Generating ccTempToken
        TestCaseHelper::setSessionToken(null);
        $creditCardService = new CreditCard(TestCaseHelper::getClient());

        $creditCardServiceParams   = [
            'sessionToken'   => TestCaseHelper::getSessionToken(),
            'userTokenId'    => TestCaseHelper::getUserTokenId(),
            'cardData'       => SimpleData::getCardData(),
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
        $response = $this->service->addUPOCreditCardByTempToken($params);
        $this->assertArrayHasKey('userPaymentOptionId', $response);
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --verbose --filter ::testAddUPOAPM$ ./tests/UserPaymentOptionsTest.php
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
        $response = $this->service->addUPOAPM($params);
        $this->assertArrayHasKey('userPaymentOptionId', $response);
        return $response['userPaymentOptionId'];
    }

    /**
     * @depends testAddUPOAPM
     * @param $userPaymentOptionId
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --verbose --filter 'testAddUPOAPM|testEditUPOAPM' ./tests/UserPaymentOptionsTest.php
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
        $response = $this->service->editUPOAPM($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @depends testAddUPOCreditCard
     * @param $addUPOCreditCardResponse
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --verbose --filter 'testAddUPOCreditCard|testEditUPOCC' ./tests/UserPaymentOptionsTest.php
     */
    public function testEditUPOCC($addUPOCreditCardResponse)
    {
        $cardData = SimpleData::getCardData();

        $params   = [
            'userTokenId'         => TestCaseHelper::getUserTokenId(),
            'clientRequestId'     => '4556',
            'userPaymentOptionId' => $addUPOCreditCardResponse['userPaymentOptionId'],
            'ccExpMonth'          => $cardData['expirationMonth'],
            'ccExpYear'           => $cardData['expirationYear'],
            'ccNameOnCard'        => 'Some new updated',
            'billingAddress'      => SimpleData::getBillingAddress(true),
        ];
        $response = $this->service->editUPOCC($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @depends testAddUPOCreditCardByTempToken
     * @depends testAddUPOAPM
     * @depends testEditUPOAPM
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     *
     * @run ./vendor/phpunit/phpunit/phpunit --verbose --filter 'testAddUPOAPM|testEditUPOAPM|testAddUPOCreditCardByTempToken|testGetUserUPOs' ./tests/UserPaymentOptionsTest.php
     */
    public function testGetUserUPOs()
    {
        $params   = [
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'clientRequestId' => '166',
        ];
        $response = $this->service->getUserUPOs($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertArrayHasKey('paymentMethods', $response);
        return $response['paymentMethods'];
    }


    /**
     * @depends testGetUserUPOs
     * @param $paymentMethods
     * @return integer
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --verbose --filter 'testAddUPOAPM|testEditUPOAPM|testAddUPOCreditCardByTempToken|testGetUserUPOs|testSuspendUPO' ./tests/UserPaymentOptionsTest.php
     */
    public function testSuspendUPO($paymentMethods)
    {
        $userPaymentOptionId = $paymentMethods[0]['userPaymentOptionId'];
        $params              = [
            'userTokenId'         => TestCaseHelper::getUserTokenId(),
            'clientRequestId'     => '188',
            'userPaymentOptionId' => $userPaymentOptionId,
        ];
        $response            = $this->service->suspendUPO($params);
        $this->assertEquals('SUCCESS', $response['status']);
        return $userPaymentOptionId;
    }

    /**
     * @depends testSuspendUPO
     * @param $userPaymentOptionId
     * @return integer
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --verbose --filter 'testAddUPOAPM|testEditUPOAPM|testAddUPOCreditCardByTempToken|testGetUserUPOs|testSuspendUPO|testEnableUPO' ./tests/UserPaymentOptionsTest.php
     */
    public function testEnableUPO($userPaymentOptionId)
    {
        $params   = [
            'userTokenId'         => TestCaseHelper::getUserTokenId(),
            'clientRequestId'     => '188',
            'userPaymentOptionId' => $userPaymentOptionId,
        ];
        $response = $this->service->enableUPO($params);
        $this->assertEquals('SUCCESS', $response['status']);
        return $userPaymentOptionId;
    }


    /**
     * @depends testSuspendUPO
     * @param $userPaymentOptionId
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     *
     * @run  ./vendor/phpunit/phpunit/phpunit --verbose --filter 'testAddUPOAPM|testEditUPOAPM|testAddUPOCreditCardByTempToken|testGetUserUPOs|testSuspendUPO|testDeleteUPO' ./tests/UserPaymentOptionsTest.php
     */
    public function testDeleteUPO($userPaymentOptionId)
    {
        $params   = [
            'userTokenId'         => TestCaseHelper::getUserTokenId(),
            'clientRequestId'     => '188',
            'userPaymentOptionId' => $userPaymentOptionId,
        ];
        $response = $this->service->deleteUPO($params);
        $this->assertEquals('SUCCESS', $response['status']);
    }


}
