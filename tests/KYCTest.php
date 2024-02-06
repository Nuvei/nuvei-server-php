<?php

use Nuvei\Api\Service\KYC;
use Nuvei\Tests\SimpleData;
use Nuvei\Tests\TestCaseHelper;
use PHPUnit\Framework\TestCase;

class KYCTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();

        $this->service = new KYC(TestCaseHelper::getClient());
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testEKYC ./tests/KYCTest.php
     */
    public function testEKYC()
    {
        $params = [
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'userId'          => sha1(time()),
            'clientUniqueId'  => '12345',
            'clientRequestId' => '100',
            'userDetails'     => SimpleData::getUserDetails(),
            'ekycUserDetails' => SimpleData::getEKYCUserDetails(),
            'customData'      => 'This parameter can be used to pass any type of information.'
        ];

        $response = $this->service->getEKYC($params);

        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetDocumentUploadUrl ./tests/KYCTest.php
     */
    public function testGetDocumentUploadUrl()
    {
        $params = [
            'userTokenId'     => TestCaseHelper::getUserTokenId(),
            'userId'          => 1,
            'clientUniqueId'  => '12345',
            'clientRequestId' => '100',
            'userDetails'     => SimpleData::getUserDetails(),
            'kycUserDetails'  => SimpleData::getEKYCUserDetails(),
            'merchantDetails' => [
                'customField1' => 'Information with the request to be saved in the API level which is not passed to the payments gateway and is not used for processing.'
            ]
        ];

        $response = $this->service->getDocumentUploadUrl($params);

        $this->assertArrayHasKey('url', $response);
        $this->assertEquals('SUCCESS', $response['status']);
    }
}
