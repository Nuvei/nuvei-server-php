<?php

namespace Nuvei\Tests;

use PHPUnit\Framework\TestCase;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\BaseService;

class BaseServiceTest extends TestCase
{
    private $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new BaseService(TestCaseHelper::getClient());
    }

    /**
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --verbose --filter testGetSessionToken ./tests/BaseServiceTest.php
     */
    public function testGetSessionToken()
    {
        $sessionToken = $this->service->getSessionToken();
        $this->assertNotEmpty($sessionToken);
    }

}
