<?php

namespace SafeCharge\Tests;

use PHPUnit\Framework\TestCase;
use SafeCharge\Api\Exception\ConnectionException;
use SafeCharge\Api\Exception\ResponseException;
use SafeCharge\Api\Exception\ValidationException;
use SafeCharge\Api\Service\BaseService;

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
     */
    public function testGetSessionToken()
    {
        $sessionToken = $this->service->getSessionToken();
        $this->assertNotEmpty($sessionToken);
    }

}
