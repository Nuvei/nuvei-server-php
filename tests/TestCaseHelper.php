<?php

namespace SafeCharge\Tests;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SafeCharge\Api\RestClient;
use SafeCharge\Api\Service\AuthenticationManagement;
use SafeCharge\Api\Service\OrdersManagement;
use SafeCharge\Api\Service\Payments\CreditCard;
use SafeCharge\Api\Service\UserPaymentOptions;
use SafeCharge\Api\Service\UsersManagement;

class TestCaseHelper
{
    private static $_client = null;

    private static $_sessionToken = null;

    private static $_userTokenId = null;

    private static $_upoCreditCardId = null;


    /**
     * @return mixed
     * @throws \Exception
     */
    public static function getClient()
    {
        if (self::$_client == null) {

            $config = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini', true);

            self::$_client = new RestClient([
                'environment'       => $config['environment'],
                'merchantId'        => $config['merchantId'],
                'merchantSiteId'    => $config['merchantSiteId'],
                'merchantSecretKey' => $config['merchantSecretKey'],
                'hashAlgorithm'     => $config['hashAlgorithm']
            ]);

            $logger = new Logger('safecharge-php-sdk');
            $logger->pushHandler(new StreamHandler(__DIR__ . DIRECTORY_SEPARATOR . 'safecharge-log.log', Logger::DEBUG));
            self::$_client->setLogger($logger);
        }
        return self::$_client;
    }

    /**
     * @return string
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public static function getSessionToken()
    {
        if (self::$_sessionToken == null) {
            $service             = new AuthenticationManagement(self::getClient());
            $response            = $service->getSessionToken(['clientRequestId' => "15"]);
            self::$_sessionToken = $response['sessionToken'];
        }

        return self::$_sessionToken;
    }

    /**
     * @param null $_sessionToken
     */
    public static function setSessionToken($_sessionToken)
    {
        self::$_sessionToken = $_sessionToken;
    }


    /**
     * @return null|string
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public static function getUserTokenId()
    {
        if (self::$_userTokenId == null) {
            $userManagementService = new UsersManagement(TestCaseHelper::getClient());
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
                throw new \Exception('Cannot create a user');
            }
            self::$_userTokenId = $userTokenId;
        }
        return self::$_userTokenId;
    }

    /**
     * @param bool $isAuth
     * @return mixed
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
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
     * @throws \Exception
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public static function openOrderAndReturnOrderId()
    {
        $service = new OrdersManagement(self::getClient());
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
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     * @throws \Exception
     */
    public static function getUPOCreditCardId()
    {
        if (!is_null(self::$_upoCreditCardId)) {
            return self::$_upoCreditCardId;
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

        self::$_upoCreditCardId = $response['userPaymentOptionId'];

        return self::$_upoCreditCardId;
    }

}