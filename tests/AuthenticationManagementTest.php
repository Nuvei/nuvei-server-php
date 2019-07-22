<?php

namespace SafeCharge\Tests;

use PHPUnit\Framework\TestCase;
use SafeCharge\Api\Exception\ConnectionException;
use SafeCharge\Api\Exception\ResponseException;
use SafeCharge\Api\Exception\ValidationException;
use SafeCharge\Api\Service\AuthenticationManagement;

class AuthenticationManagementTest extends TestCase
{
    private $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new AuthenticationManagement(TestCaseHelper::getClient());
    }

    /**
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     */
    public function testGetSessionToken()
    {
        $response = $this->service->getSessionToken(['clientRequestId' => "15"]);
        $this->assertContains('sessionToken', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

}
