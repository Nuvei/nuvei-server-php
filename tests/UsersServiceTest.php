<?php

namespace Nuvei\Tests;

use PHPUnit\Framework\TestCase;
use Nuvei\Api\Exception\ConnectionException;
use Nuvei\Api\Exception\ResponseException;
use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Service\UserService;

class UsersServiceTest extends TestCase
{
    private $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new UserService(TestCaseHelper::getClient());
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testCreateUser ./tests/UsersServiceTest.php
     */
    public function testCreateUser()
    {
        $userTokenId = sha1(time());
        $params      = [
            'userTokenId'     => $userTokenId,
            'clientRequestId' => '100',
            'firstName'       => 'John',
            'lastName'        => 'Smith',
            'address'         => 'some street',
            'state'           => '',
            'city'            => '',
            'zip'             => '',
            'countryCode'     => 'GB',
            'county'          => 'Anchorage',
            'phone'           => '',
            'locale'          => 'en_UK',
            'email'           => 'john.smith@test.com',
        ];

        $response = $this->service->createUser($params);
        //$this->assertContains('userId', $response);
        $this->assertEquals('SUCCESS', $response['status']);
        return $userTokenId;
    }

    /**
     * @depends testCreateUser
     *
     * @param $userTokenId
     *
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testUpdateUser ./tests/UsersServiceTest.php
     */
    public function testUpdateUser($userTokenId)
    {
        $params   = [
            'userTokenId'     => $userTokenId,
            'clientRequestId' => '101',
            'firstName'       => 'John',
            'lastName'        => 'Smith-updated',
            'address'         => 'some street-updated',
            'state'           => '',
            'city'            => 'London',
            'zip'             => '',
            'countryCode'     => 'GB',
            'county'          => 'Anchorage',
            'phone'           => '',
            'locale'          => 'en_UK',
            'email'           => 'john.smith@test.com',
        ];

        $response = $this->service->updateUser($params);
        $this->assertEquals('SUCCESS', $response['status']);
        //$this->assertContains('userId', $response);
        return $userTokenId;
    }

    /**
     * @depends testUpdateUser
     *
     * @param $userTokenId
     *
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetUserDetails ./tests/UsersServiceTest.php
     */
    public function testGetUserDetails($userTokenId)
    {
        $params   = [
            'userTokenId'     => $userTokenId,
            'clientRequestId' => '102',
        ];
        $response = $this->service->getUserDetails($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertContains('userId', $response);
        $this->assertEquals('Smith-updated', $response['userDetails']['lastName']);
        $this->assertEquals('some street-updated', $response['userDetails']['address']);

    }

}
