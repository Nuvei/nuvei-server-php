<?php

namespace SafeCharge\Tests;

use SafeCharge\Api\Service\UsersManagement;

class UsersManagementTest extends \PHPUnit_Framework_TestCase
{
    private $_service;

    public function __construct()
    {
        $this->_service = new UsersManagement(TestCaseHelper::getClient());
    }

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

        $response = $this->_service->createUser($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertContains('userId', $response);
        return $userTokenId;
    }

    /**
     * @depends testCreateUser
     * @param $userTokenId
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
        $response = $this->_service->updateUser($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertContains('userId', $response);
        return $userTokenId;
    }

    /**
     * @depends testUpdateUser
     * @param $userTokenId
     */
    public function testGetUserDetails($userTokenId)
    {
        $params   = [
            'userTokenId'     => $userTokenId,
            'clientRequestId' => '102',
        ];
        $response = $this->_service->getUserDetails($params);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertContains('userId', $response);
        $this->assertEquals('Smith-updated', $response['userDetails']['lastName']);
        $this->assertEquals('some street-updated', $response['userDetails']['address']);

    }

}
