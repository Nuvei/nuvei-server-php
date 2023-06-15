<?php

namespace Nuvei\Api\Service\Payments;

use Nuvei\Api\RestClient;
use Nuvei\Api\Service\BaseService;
use Nuvei\Api\Utils;

/**
 * Class Subscription
 * @package Nuvei\Api\Service\Payments
 */
class Subscription extends BaseService
{

    /**
     * Subscription constructor.
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
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#createSubscription
     */
    public function createSubscription(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'planId',
            'userTokenId',
            'userPaymentOptionId',
            'endAfter',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'planId',
            'userPaymentOptionId',
            'initialAmount',
            'recurringAmount',
            'currency',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'createSubscription.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#cancelSubscription
     */
    public function cancelSubscription(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'subscriptionId',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'subscriptionId',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'cancelSubscription.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getSubscriptionsList
     */
    public function getSubscriptionsList(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'planId',
            'subscriptionId',
            'subscriptionStatus',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getSubscriptionsList.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     */
    public function getSubscriptionPlans(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getSubscriptionPlans.do');
    }
}
