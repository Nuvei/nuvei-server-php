<?php

namespace Nuvei\Api\Service\Payments;

use Nuvei\Api\RestClient;
use Nuvei\Api\Service\BaseService;
use Nuvei\Api\Utils;

/**
 * Class ThreeDsecure
 * @package Nuvei\Api\Service\Payments
 */
class ThreeDsecure extends BaseService
{

    /**
     * ThreeDsecure constructor.
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
     * @link https://docs.nuvei.com/api/deprecated/indexDeprecated.html?json#dynamic3D
     */
    public function dynamic3D(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'isDynamic3D',
            'currency',
            'amount',
            'amountDetails',
            'items',
            'deviceDetails',
            'billingAddress',
            'relatedTransactionId',
            'timeStamp',
            'checksum'
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

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'dynamic3D.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/deprecated/indexDeprecated.html?json#payment3D
     */
    public function payment3D(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'currency',
            'amount',
            'amountDetails',
            'items',
            'billingAddress',
            'paymentMethod',
            'timeStamp',
            'checksum',
            'deviceDetails'
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

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);
        return $this->requestJson($params, 'payment3D.do');
    }
}
