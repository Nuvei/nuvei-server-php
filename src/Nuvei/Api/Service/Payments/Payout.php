<?php

namespace Nuvei\Api\Service\Payments;

use Nuvei\Api\RestClient;
use Nuvei\Api\Service\BaseService;
use Nuvei\Api\Utils;

/**
 * Class Payout
 * @package Nuvei\Api\Service\Payments
 */
class Payout extends BaseService
{

    /**
     * Payout constructor.
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
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#payout
     */
    public function payout(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientUniqueId',
            'amount',
            'currency',
            'userPaymentOption' => [
                'userPaymentOptionId'
            ],
            'timeStamp',
            'checksum',
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'amount',
            'currency',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $params = $this->appendIpAddress($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'payout.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#getPayoutStatus
     */
    public function getPayoutStatus(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'timeStamp',
            'checksum',
            'clientRequestId'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getPayoutStatus.do');
    }


}
