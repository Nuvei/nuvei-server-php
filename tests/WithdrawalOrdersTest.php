<?php
use Nuvei\Api\Service\Withdrawals\Orders;
use Nuvei\Tests\TestCaseHelper;
use PHPUnit\Framework\TestCase;

class WithdrawalOrdersTest extends TestCase
{
    private $__service;

    public function __construct()
    {
        parent::__construct();

        $this->service = new Orders(TestCaseHelper::getClient());
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetOrders ./tests/WithdrawalOrdersTest.php
     */
    public function testGetOrders()
    {
        $params = [
            'merchantUniqueId' => '',
        ];

        $response = $this->service->getOrders($params);

        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertGreaterThan(0, $response['totalCount']);
        $this->assertArrayHasKey('totalCount', $response);
        $this->assertArrayHasKey('withdrawalOrders', $response);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testSettleWithdrawalOrder ./tests/WithdrawalOrdersTest.php
     */
    public function testSettleWithdrawalOrder()
    {
        $approvedOrder = TestCaseHelper::generateApprovedOrder();

        $params = [
            'wdOrderId' => $approvedOrder['wdOrderId'],
        ];

        $response = $this->service->settleWithdrawalOrder($params);

        $this->assertEquals('Settled', $response['wdOrderStatus']);
        $this->assertEquals($approvedOrder['wdOrderId'], $response['wdOrderId']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetOrderIds ./tests/WithdrawalOrdersTest.php
     */
    public function testGetOrderIds()
    {
        $params = [
            'merchantUniqueId' => '',
        ];

        $response = $this->service->getOrderIds($params);

        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertGreaterThan(0, $response['totalCount']);
        $this->assertArrayHasKey('totalCount', $response);
        $this->assertArrayHasKey('wdOrderIds', $response);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testSettleOrdersInBatch ./tests/WithdrawalOrdersTest.php
     */
    public function testSettleOrdersInBatch()
    {
        $approvedOrderId  = TestCaseHelper::generateApprovedOrder()['wdOrderId'];
        $ordersBatchArray = [
            $approvedOrderId, //get the last order id
        ];

        $params = [
            'wdOrderIds' => $ordersBatchArray,
        ];

        $response = $this->service->settleOrdersInBatch($params);

        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertEquals('SUCCESS', $response['orders'][0]['operationStatus']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testDeleteWithdrawalOrder ./tests/WithdrawalOrdersTest.php
     */
    public function testDeleteWithdrawalOrder()
    {
        $approvedOrder = TestCaseHelper::generateApprovedOrder();

        $params = [
            'wdOrderId' => $approvedOrder['wdOrderId'],
        ];

        $response = $this->service->deleteWithdrawalOrder($params);

        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testUpdateOrdersDetails ./tests/WithdrawalOrdersTest.php
     */
    public function testUpdateOrdersDetails()
    {
        $approvedOrder = TestCaseHelper::generateApprovedOrder();

        $params = [
            "wdOrderIds"    => [ (string)$approvedOrder["wdOrderId"] ],
            "operatorName" => "Test Name",
            "details"      => [
                "executionTime" => (new DateTime())->format('YmdHi'),
            ],
        ];

        $response = $this->service->updateOrdersDetails($params);

        $this->assertSame('', json_encode($response));
        $this->assertEquals('SUCCESS', $response['status']);
    }
}
