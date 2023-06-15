<?php

namespace Nuvei\Tests;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use Nuvei\Api\Exception\ConfigurationException;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Nuvei;
use Nuvei\Api\Service\PaymentService;
use Nuvei\Api\Service\UserService;

class NuveiTest extends TestCase
{

    public function testInitialize()
    {
        $config = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini', true);

        $nuvei = new Nuvei();
        $nuvei->initialize($config);

        $logger = new Logger('nuvei-php-sdk');
        $logger->pushHandler(new StreamHandler(__DIR__ . DIRECTORY_SEPARATOR . 'nuvei-log.log', Logger::DEBUG));
        $nuvei->setLogger($logger);

        $this->assertInstanceOf(Nuvei::class, $nuvei);
        return $nuvei;
    }

    /**
     * @depends testInitialize
     *
     * @param Nuvei $nuvei
     *
     * @return mixed
     * @throws ConfigurationException
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public function testGetSessionToken(Nuvei $nuvei)
    {
        $sessionToken = $nuvei->getSessionToken();
        $this->assertNotEmpty($sessionToken);
        return $sessionToken;
    }

    /**
     * @depends testInitialize
     *
     * @param Nuvei $nuvei
     *
     * @throws ConfigurationException
     */
    public function testGetPaymentService($nuvei)
    {
        $paymentService = $nuvei->getPaymentService();
        $this->assertInstanceOf(PaymentService::class, $paymentService);
    }

    /**
     * @depends testInitialize
     *
     * @param Nuvei $nuvei
     *
     * @throws ConfigurationException
     */
    public function testGetUserService($nuvei)
    {
        $paymentService = $nuvei->getUserService();
        $this->assertInstanceOf(UserService::class, $paymentService);
    }
}
