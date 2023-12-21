<?php

use Nuvei\Api\Service\Withdrawals\NetDeposits;
use Nuvei\Tests\SimpleData;
use Nuvei\Tests\TestCaseHelper;
use PHPUnit\Framework\TestCase;

class WithdrawalNetDepositsTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();

        $this->service = new NetDeposits(TestCaseHelper::getClient());
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetNetDeposits ./tests/WithdrawalNetDepositsTest.php
     */
    public function testGetNetDeposits()
    {
        $params = [
            'userTokenId' => TestCaseHelper::getUserTokenId(),
            'currency'    => SimpleData::getCurrency(),
            'userPMId'    => TestCaseHelper::getUserPaymentOptionId(),
        ];

        $response = $this->service->getNetDeposits($params);

        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertArrayHasKey('netDeposits', $response);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testUpdateNetDepositValue ./tests/WithdrawalNetDepositsTest.php
     */
    public function testUpdateNetDepositValue()
    {
        $params = [
            'userTokenId'  => TestCaseHelper::getUserTokenId(),
            'userPMId'     => TestCaseHelper::getUserPaymentOptionId(),
            'amount'       => SimpleData::getAmount(),
            'movementType' => 2,// Allowed: 2 – Manual Deposit 3 – Manual Withdrawal
            'currency'     => SimpleData::getCurrency(),
        ];

        $response = $this->service->updateNetDepositValue($params);

        $this->assertEquals('SUCCESS', $response['status']);
    }

    /**
     * @return string
     * @throws ConnectionException
     * @throws ResponseException
     * @throws ValidationException
     * @run ./vendor/phpunit/phpunit/phpunit --filter testGetUserPaymentMethodNetDeposits ./tests/WithdrawalNetDepositsTest.php
     */
    public function testGetUserPaymentMethodNetDeposits()
    {
        $params = [
            'userTokenId' => TestCaseHelper::getUserTokenId(),
            'currency'    => SimpleData::getCurrency(),
            'country'     => SimpleData::getCountry(),
            'userPMId'    => TestCaseHelper::getUserPaymentOptionId(),
        ];

        $response = $this->service->getUserPaymentMethodNetDeposits($params);

        $this->assertSame([], $response);
        $this->assertEquals('SUCCESS', $response['status']);
        $this->assertArrayHasKey('userPaymentMethods', $response);
    }
}
