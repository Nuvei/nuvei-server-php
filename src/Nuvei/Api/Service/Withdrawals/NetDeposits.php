<?php
namespace Nuvei\Api\Service\Withdrawals;

use Nuvei\Api\Service\BaseService;
use Nuvei\Api\RestClient;
use Nuvei\Api\Utils;

class NetDeposits extends BaseService
{
    /**
     * Orders constructor.
     *
     * @param RestClient $client
     *
     * @throws \Nuvei\Api\Exception\ConfigurationException
     */
    public function __construct(RestClient $client)
    {
        parent::__construct($client);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getNetDeposits
     */
    public function getNetDeposits(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'currency',
            'userPMId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/getNetDeposits.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#updateNetDepositValue
     */
    public function updateNetDepositValue(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'userPMId',
            'amount',
            'movementType', // Allowed: 2 – Manual Deposit 3 – Manual Withdrawal
            'currency',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/updateNetDepositValue.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getUserPaymentMethodNetDeposits
     */
    public function getUserPaymentMethodNetDeposits(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'currency',
            'userPMId',
            'timeStamp',
            'checksum',
        ];

        return $this->call($params, $mandatory, '../withdrawal/getUserPaymentMethodNetDeposits.do');
    }
}