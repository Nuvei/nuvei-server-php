<?php

namespace Nuvei\Tests;

use Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Nuvei\Api\Exception\ConfigurationException;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\RestClient;
use Nuvei\Api\Service\AuthenticationManagement;
use Nuvei\Api\Service\BaseService;
use Nuvei\Api\Service\OrdersManagement;
use Nuvei\Api\Service\Payments\CreditCard;
use Nuvei\Api\Service\PaymentService;
use Nuvei\Api\Service\UserPaymentOptions;
use Nuvei\Api\Service\UserService;
use Nuvei\Api\Service\Withdrawals\Orders;
use Nuvei\Api\Service\Withdrawals\Requests;
use Nuvei\Api\Service\Withdrawals\Processing;

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
                'sslVerifyPeer'     => $config['sslVerifyPeer'],
                'merchantId'        => $config['merchantId'],
                'merchantSiteId'    => $config['merchantSiteId'],
                'merchantSecretKey' => $config['merchantSecretKey'],
                'hashAlgorithm'     => $config['hashAlgorithm'],
                'debugMode'         => $config['debugMode'],
            ]);

            $logger = new Logger('nuvei-php-sdk');
            $logger->pushHandler(new StreamHandler(__DIR__ . DIRECTORY_SEPARATOR. 'nuvei-log.log', Logger::DEBUG));

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
            'cardData'          => SimpleData::getCardData(),
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

        $cardData = SimpleData::getCardData('375510288656924');

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

    public static function getUserPaymentOptionId()
    {
        $service = new UserPaymentOptions(self::getClient());
        $response = $service->getUserUPOs([
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'clientRequestId' => '235',
        ]);

        try {
            return $response['paymentMethods'][0]['userPaymentOptionId'];
        } catch(Exception $e) {
            return self::getUPOCreditCardId();
        }
    }

    public static function getDocumentType()
    {
        $types = [
            'TermsAndConditions',
            'PrivacyPolicy',
            'FAQ',
        ];

        return $types[array_rand($types)];
    }

    //get random rebilling plan name from combination of random string and timestamp
    public static function getPlanName()
    {
        //create random string
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        $length = rand(5, 10);
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $timestamp = time();
        return "Plan Name $randomString - $timestamp";
    }
    
    public static function generateDefaultRequest() 
    {
        
        $params = [
            'userTokenId'         => TestCaseHelper::getUserTokenId(),
            'userPMId'            => TestCaseHelper::getUserPaymentOptionId(),
            'amount'              => SimpleData::getAmount(100),
            'currency'            => SimpleData::getCurrency(),
            'merchantWDRequestId' => SimpleData::generateWithdrawalRequestId(),
            'merchantUniqueId'    => SimpleData::generateMerchantUniqueID(),
            // 'userPaymentOption'   => [
            //     'alternativePaymentMethod' => [
            //         'paymentMethod' => 'apmgw_VIP_Preferred',
            //     ],
            // ],
        ];

        $service = new Requests(self::getClient());
        return $service->submitRequest($params);
    }

    public static function generateDefaultOrder() : array
    {
        $withdrawalRequest = self::generateDefaultRequest();
        $params = [
            'wdRequestId'           => $withdrawalRequest['wdRequestId'],
            'merchantWDRequestId'   => SimpleData::generateMerchantUniqueID(),
            'userPMId'              => self::getUPOCreditCardId(),
            'userPMId'              => self::getUserPaymentOptionId(),
            'amount'                => SimpleData::getAmount(100),
            'currency'              => SimpleData::getCurrency(),
            'settlementType'        => SimpleData::generateSettlementType(),
        ];

        $service = new Processing(self::getClient());
        return $service->placeWithdrawalOrder($params);
    }

    public static function generateApprovedOrder() : array
    {
        $withdrawalRequest = self::generateDefaultRequest();
        $params = [
            'wdRequestId' => $withdrawalRequest['wdRequestId'],
            'merchantWDRequestId' => SimpleData::generateMerchantUniqueID(),
        ];

        $service = new Processing(self::getClient());
        return $service->approveRequest($params);
    }

    public static function generateMinimalWithdrawalOrder($amount = 0.01) : array
    {
        $withdrawalRequest = self::generateDefaultRequest();
        $params = [
            'wdRequestId'           => $withdrawalRequest['wdRequestId'],
            'merchantWDRequestId'   => SimpleData::generateMerchantUniqueID(),
            'userPMId'              => self::getUPOCreditCardId(),
            'userPMId'              => self::getUserPaymentOptionId(),
            'amount'                => SimpleData::getAmount($amount),
            'currency'              => SimpleData::getCurrency(),
            'settlementType'        => SimpleData::generateSettlementType(),
        ];

        $service = new Processing(self::getClient());
        return $service->placeWithdrawalOrder($params);
    }
}