<?php
namespace Nuvei\Api\Service\Withdrawals;

use Nuvei\Api\Service\BaseService;
use Nuvei\Api\RestClient;
use Nuvei\Api\Utils;

class Requests extends BaseService
{
    /**
     * Rebilling constructor.
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
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#submitRequest
     */
    public function submitRequest(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'userPMId',
            'amount',
            'currency',
            'merchantWDRequestId',
            'merchantUniqueId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/submitRequest.do', null, true);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getRequests
     */
    public function getRequests(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/getRequests.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getCandidatesForRefund
     */
    public function getCandidatesForRefund(array $params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'wdRequestId',
            'timeStamp',
            'checksum'
        ];

        return $this->call($params, $mandatory, '../withdrawal/getCandidatesForRefund.do');
    }
}