<?php
use PHPUnit\Framework\TestCase;
use Nuvei\Api\Service\Withdrawals\Requests;
use Nuvei\Tests\TestCaseHelper;

class WithdrawalRequestsTest extends TestCase
{
    private $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new Requests(TestCaseHelper::getClient());
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testSubmitRequest ./tests/WithdrawalRequestsTest.php
     */
    public function testSubmitRequest()
    {
        $response = TestCaseHelper::generateDefaultRequest();

        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertEquals('Pending', $response['wdRequestStatus']);
    }

    //test getRequests
    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetRequests ./tests/WithdrawalRequestsTest.php
     */
    public function testGetRequests()
    {
        $response = $this->service->getRequests([]);

        $this->assertArrayHasKey('totalCount', $response);
        $this->assertThat($response, $this->logicalOr(
            $this->arrayHasKey('withdrawalRequest'),
            $this->arrayHasKey('withdrawalRequests')
        ));

        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetCandidatesForRefund ./tests/WithdrawalRequestsTest.php
     */
    public function testGetCandidatesForRefund()
    {
        $withdrawalRequest = TestCaseHelper::generateDefaultRequest();
        $params = [
            'wdRequestId' => $withdrawalRequest['wdRequestId'],
        ];

        $response = $this->service->getCandidatesForRefund($params);
        $this->assertThat($response, $this->logicalOr(
            $this->arrayHasKey('merchantId'),
            $this->arrayHasKey('merchantSiteId'),
            $this->arrayHasKey('transactionsDetailList'),
        ));
        $this->assertEquals('SUCCESS', $response['status']);
    }
}
