<?php
namespace Nuvei\Api\Service;

use Nuvei\Api\Service\BaseService;
use Nuvei\Api\RestClient;

class KYC extends BaseService
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
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#eKYC
     */
    public function getEKYC($params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'userId',
            'clientUniqueId',
            'clientRequestId',
            'userDetails',
            'ekycUserDetails',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'timeStamp',
            'merchantSecretKey'
        ];

        return $this->call($params, $mandatory, 'eKYC.do', $checksumParametersOrder, true, true, false);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getDocumentUploadUrl
     */
    public function getDocumentUploadUrl($params = [])
    {
        $mandatory = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'userId',
            'clientUniqueId',
            'clientRequestId',
            'userDetails',
            'kycUserDetails',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'timeStamp',
            'merchantSecretKey'
        ];

        return $this->call($params, $mandatory, 'getDocumentUploadUrl.do', $checksumParametersOrder, true, true, false);
    }
}