<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\AuthenticationManagement;

class AuthenticationManagementTest extends \PHPUnit_Framework_TestCase
{
    private $_service;

    public function __construct()
    {
        $this->_service = new AuthenticationManagement(TestCaseHelper::getClient());
    }

    /**
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function testGetSessionToken()
    {
        $response = $this->_service->getSessionToken(['clientRequestId' => "15"]);
        $this->assertContains('sessionToken', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }

}
