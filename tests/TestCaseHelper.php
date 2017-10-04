<?php

namespace SafeCharge\Tests;


use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use SafeCharge\Api\RestClient;
use SafeCharge\Api\Service\AuthenticationManagement;
use SafeCharge\Api\Service\Payments\CreditCard;
use SafeCharge\Api\Service\UsersManagement;

class TestCaseHelper extends \PHPUnit_Framework_TestCase
{
    private static $_client = null;

    private static $_sessionToken = null;

    private static $_userTokenId = null;


    /**
     * @return mixed
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

    public static function createAndReturnTransaction($amount = 10, $isAuth = false)
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
            'currency'          => 'EUR',
            'amount'            => $amount,
            'amountDetails'     => SimpleData::getAmountDetails(),
            'items'             => SimpleData::getItems(),
            'deviceDetails'     => SimpleData::getDeviceDetails(),
            'userDetails'       => SimpleData::getUserDetails(),
            'shippingAddress'   => SimpleData::getShippingAddress(),
            'billingAddress'    => SimpleData::getBillingAddress(),
            'dynamicDescriptor' => SimpleData::getDynamicDescriptor(),
            'merchantDetails'   => SimpleData::getMerchantDetails(),
            'addendums'         => [
                'localPayment' => [
                    'nationalId'            => '012345678',
                    'debitType'             => '2',
                    'firstInstallment'      => '4',
                    'periodicalInstallment' => '3',
                    'numberOfInstallments'  => '3'
                ]
            ],
            'cardData'          => SimpleData::getCarData(),
            'urlDetails'        => SimpleData::getUrlDetails()
        ];

        $response = $service->paymentCC($params);
        return $response;
    }

}