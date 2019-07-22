<?php

namespace SafeCharge\Tests;


use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SafeCharge\Api\Exception\ConfigurationException;
use SafeCharge\Api\Exception\ConnectionException;
use SafeCharge\Api\Exception\ResponseException;
use SafeCharge\Api\Exception\ValidationException;
use SafeCharge\Api\RestClient;
use SafeCharge\Api\Service\AuthenticationManagement;
use SafeCharge\Api\Service\BaseService;
use SafeCharge\Api\Service\OrdersManagement;
use SafeCharge\Api\Service\Payments\CreditCard;
use SafeCharge\Api\Service\PaymentService;
use SafeCharge\Api\Service\UserPaymentOptions;
use SafeCharge\Api\Service\UserService;

class TestCaseHelper
{
    private static $client = null;

    private static $sessionToken = null;

    private static $userTokenId = null;

    private static $upoCreditCardId = null;


    /**
     * @return mixed
     * @throws Exception
     */
    public static function getClient()
    {
        if (self::$client == null) {

            $config = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini', true);

            self::$client = new RestClient([
                'environment'       => $config['environment'],
                'merchantId'        => $config['merchantId'],
                'merchantSiteId'    => $config['merchantSiteId'],
                'merchantSecretKey' => $config['merchantSecretKey'],
                'hashAlgorithm'     => $config['hashAlgorithm']
            ]);

            $logger = new Logger('safecharge-php-sdk');
            $logger->pushHandler(new StreamHandler(__DIR__ . DIRECTORY_SEPARATOR . 'safecharge-log.log', Logger::DEBUG));
            self::$client->setLogger($logger);
        }
        return self::$client;
    }

    /**
     * @return string
     * @throws ConfigurationException
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public static function getSessionToken()
    {
        if (self::$sessionToken == null) {
            $service            = new BaseService(self::getClient());
            self::$sessionToken = $service->getSessionToken();
        }

        return self::$sessionToken;
    }

    /**
     * @param null $sessionToken
     */
    public static function setSessionToken($sessionToken)
    {
        self::$sessionToken = $sessionToken;
    }


    /**
     * @return null|string
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public static function getUserTokenId()
    {
        if (self::$userTokenId == null) {
            $userManagementService = new UserService(TestCaseHelper::getClient());
            $userTokenId           = md5(time());
            $params                = [
                'userTokenId'     => $userTokenId,
                'clientRequestId' => '100',
                'firstName'       => 'John',
                'lastName'        => 'Smith',
                'address'         => 'some street',
                'state'           => '',
                'city'            => '',
                'zip'             => '',
                'countryCode'     => 'GB',
                'phone'           => '',
                'locale'          => 'en_UK',
                'email'           => 'john.smith@test.com',
            ];

            $response = $userManagementService->createUser($params);
            if (!isset($response['status']) || $response['status'] != 'SUCCESS') {
                throw new Exception('Cannot create a user');
            }
            self::$userTokenId = $userTokenId;
        }
        return self::$userTokenId;
    }

    /**
     * @param bool $isAuth
     *
     * @return mixed
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public static function createAndReturnTransaction($isAuth = false)
    {
        $service = new CreditCard(self::getClient());
        self::setSessionToken(null);
        $params = [
            'sessionToken'      => self::getSessionToken(),
            // "orderId"           => "",
            'userTokenId'       => TestCaseHelper::getUserTokenId(),
            'clientRequestId'   => '',
            'transactionType'   => $isAuth ? 'Auth' : 'Sale',
            'isRebilling'       => '0',
            'isPartialApproval' => '0',
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
            'cardData'          => SimpleData::getCarData(),
            'urlDetails'        => SimpleData::getUrlDetails()
        ];

        $response = $service->paymentCC($params);
        return $response;
    }

    /**
     * @return mixed
     * @throws Exception
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public static function openOrderAndReturnOrderId()
    {
        $service = new PaymentService(self::getClient());
        self::setSessionToken(null);
        $params = [
            'sessionToken'      => TestCaseHelper::getSessionToken(),
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

        $response = $service->openOrder($params);
        return $response['orderId'];
    }

    /**
     * @return mixed
     * @throws ConfigurationException
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @throws Exception
     */
    public static function getUPOCreditCardId()
    {
        if (!is_null(self::$upoCreditCardId)) {
            return self::$upoCreditCardId;
        }
        $service = new UserPaymentOptions(self::getClient());

        $cardData = SimpleData::getCarData('375510288656924');

        $params = [
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'clientRequestId' => '235',
            'ccCardNumber'    => $cardData['cardNumber'],
            'ccExpMonth'      => $cardData['expirationMonth'],
            'ccExpYear'       => $cardData['expirationYear'],
            'ccNameOnCard'    => $cardData['cardHolderName'],
        ];

        $response = $service->addUPOCreditCard($params);

        self::$upoCreditCardId = $response['userPaymentOptionId'];

        return self::$upoCreditCardId;
    }

}