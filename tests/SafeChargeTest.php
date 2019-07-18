<?php

namespace SafeCharge\Tests;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use SafeCharge\Api\SafeCharge;
use SafeCharge\Api\Service\PaymentService;
use SafeCharge\Api\Service\UserService;

class SafeChargeTest extends TestCase
{

    public function testInitialize()
    {
        $config = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini', true);

        $safecharge = new SafeCharge();
        $safecharge->initialize($config);

        $logger = new Logger('safecharge-php-sdk');
        $logger->pushHandler(new StreamHandler(__DIR__ . DIRECTORY_SEPARATOR . 'safecharge-log.log', Logger::DEBUG));
        $safecharge->setLogger($logger);

        $this->assertInstanceOf(SafeCharge::class, $safecharge);
        return $safecharge;
    }

    /**
     * @depends testInitialize
     *
     * @param SafeCharge $safecharge
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testGetSessionToken(SafeCharge $safecharge)
    {
        $sessionToken = $safecharge->getSessionToken();
        $this->assertNotEmpty($sessionToken);
        return $sessionToken;
    }

    /**
     * @depends testInitialize
     *
     * @param SafeCharge $safecharge
     *
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     */
    public function testGetPaymentService($safecharge)
    {
        $paymentService = $safecharge->getPaymentService();
        $this->assertInstanceOf(PaymentService::class, $paymentService);
    }

    /**
     * @depends testInitialize
     *
     * @param SafeCharge $safecharge
     *
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     */
    public function testGetUserService($safecharge)
    {
        $paymentService = $safecharge->getUserService();
        $this->assertInstanceOf(UserService::class, $paymentService);
    }


}
