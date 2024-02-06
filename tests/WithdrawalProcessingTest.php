<?php
use PHPUnit\Framework\TestCase;
use Nuvei\Api\Service\Withdrawals\Processing;
use Nuvei\Tests\TestCaseHelper;

class WithdrawalProcessing extends TestCase
{
    private $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new Processing(TestCaseHelper::getClient());
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testApproveRequest ./tests/WithdrawalProcessingTest.php
     */
    public function testApproveRequest()
    {
        $response = TestCaseHelper::generateApprovedOrder();

        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertEquals('InProgress', $response['wdRequestStatus']);
        $this->assertEquals('Approved', $response['wdOrderStatus']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testCancelRequest ./tests/WithdrawalProcessingTest.php
     */
    public function testCancelRequest()
    {
        $approvedOrder = TestCaseHelper::generateApprovedOrder();

        $params = [
            'userTokenId' => TestCaseHelper::getUserTokenId(),
            'wdRequestId' => $approvedOrder['wdRequestId'],
            'merchantWDRequestId' => $approvedOrder['merchantWDRequestId']
        ];

        $response = $this->service->cancelRequest($params);

        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertEquals('Canceled', $response['wdRequestStatus']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testDeclineRequest ./tests/WithdrawalProcessingTest.php
     */
    public function testDeclineRequest()
    {
        $approvedOrder = TestCaseHelper::generateApprovedOrder();

        $params = [
            'wdRequestId' => $approvedOrder['wdRequestId'],
            'merchantWDRequestId' => $approvedOrder['merchantWDRequestId']
        ];

        $response = $this->service->declineRequest($params);

        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertEquals('Declined', $response['wdRequestStatus']);
    }

    //test sealRequest
    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testSealRequest ./tests/WithdrawalProcessingTest.php
     */
    public function testSealRequest()
    {
        $approvedOrder = TestCaseHelper::generateMinimalWithdrawalOrder(0.01);

        $params = [
            'wdRequestId' => $approvedOrder['wdRequestId'],
            'merchantWDRequestId' => $approvedOrder['merchantWDRequestId'],
            'userAccountId' => '12345',
            'operatorName' => 'Test Operator',
        ];

        $response = $this->service->sealRequest($params);

        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertEquals('InProgress', $response['wdRequestStatus']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testPlaceWithdrawalOrder ./tests/WithdrawalProcessingTest.php
     */
    public function testPlaceWithdrawalOrder()
    {
        $response = TestCaseHelper::generateDefaultOrder();

        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertEquals('InProgress', $response['wdRequestStatus']);
        $this->assertEquals('Approved', $response['wdOrderStatus']);
    }
}