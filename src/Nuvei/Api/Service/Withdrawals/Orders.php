<?php
namespace Nuvei\Api\Service\Withdrawals;

use Nuvei\Api\Service\BaseService;
use Nuvei\Api\RestClient;
use Nuvei\Api\Utils;

class Orders extends BaseService
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
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getOrders
     */
    public function getOrders(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'merchantUniqueId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/getOrders.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#settleWithdrawalOrder
     */ 
    public function settleWithdrawalOrder(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'wdOrderId',
            'timeStamp',
            'checksum'
        ];
        
        return $this->call($params, $mandatory, '../withdrawal/settleWithdrawalOrder.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getOrderIds
     */
    public function getOrderIds(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'merchantUniqueId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/getOrderIds.do', null, true);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#settleOrdersInBatch
     */
    public function settleOrdersInBatch(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'wdOrderIds',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/settleOrdersInBatch.do', null, true);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#deleteWithdrawalOrder
     */
    public function deleteWithdrawalOrder(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'wdOrderId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/deleteWithdrawalOrder.do', null, true);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#updateOrdersDetails
     */
    public function updateOrdersDetails(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'wdOrderIds',
            'details',
            'timeStamp',
            'checksum',
        ];

        return $this->call($params, $mandatory, '../withdrawal/updateOrdersDetails.do', null, false);
    }
}