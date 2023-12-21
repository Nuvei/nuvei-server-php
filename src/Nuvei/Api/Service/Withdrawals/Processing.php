<?php
namespace Nuvei\Api\Service\Withdrawals;

use Nuvei\Api\Service\BaseService;
use Nuvei\Api\RestClient;
use Nuvei\Api\Utils;

class Processing extends BaseService
{
    /**
     * Processing constructor.
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
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#approveRequest
     */
    public function approveRequest(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'wdRequestId',
            'merchantWDRequestId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/approveRequest.do', null, true);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#cancelRequest
     */
    public function cancelRequest(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'wdRequestId',
            'merchantWDRequestId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/cancelRequest.do', null, true);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#declineRequest
     */
    public function declineRequest(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'wdRequestId',
            'merchantWDRequestId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/declineRequest.do', null, true);
    }

    /**
     * @param array $params
     * 
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#sealRequest
     */
    public function sealRequest(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'wdRequestId',
            'merchantWDRequestId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/sealRequest.do', null, true, true, true);
    }


    /**
     * placeWithdrawalOrder (Split Withdrawal)
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#placeWithdrawalOrderSplitWithdrawal
     */
    public function placeWithdrawalOrder(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'wdRequestId',
            'merchantWDRequestId',
            'userPMId',
            'amount',
            'currency',
            'settlementType',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/placeWithdrawalOrder.do');
    }
}