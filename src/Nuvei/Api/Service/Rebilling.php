<?php
namespace Nuvei\Api\Service;

use Nuvei\Api\RestClient;
use Nuvei\Api\Utils;

/**
 * Class AdvancedAPMIntegration
 * @package Nuvei\Api\Service
 */
class Rebilling extends BaseService
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
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getPlansList
     */
    public function getPlansList(array $params = [])
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'timeStamp',
            'checksum'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $params['checksum'] = Utils::calculateChecksum(
            $params,
            ['merchantId', 'merchantSiteId', 'timeStamp'],
            $this->client->getConfig()->getMerchantSecretKey(),
            $this->client->getConfig()->getHashAlgorithm()
        );
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getPlansList.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#createPlan
     */
    public function createPlan(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'name',
            'recurringAmount',
            'currency',
            'endAfter' => [
                'day',
                'month',
                'year',
            ],
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'name',
            'initialAmount',
            'recurringAmount',
            'currency',
            'timeStamp',
        ];

        if(!isset($params['initialAmount'])) {
            $params['initialAmount'] = $params['recurringAmount'];
        }
        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $params['checksum'] = Utils::calculateChecksum(
            $params,
            $checksumParametersOrder, 
            $this->client->getConfig()->getMerchantSecretKey(),
            $this->client->getConfig()->getHashAlgorithm()
        );
        $this->validate($params, $mandatoryFields);

        $return = $this->requestJson($params, 'createPlan.do');
        $return['params'] = $params;
        return $return;
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#editPlan
     */
    public function editPlan(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'planId',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'planId',
            'initialAmount',
            'recurringAmount',
            'currency',
            'timeStamp',
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $params['checksum'] = Utils::calculateChecksum(
            $params,
            $checksumParametersOrder, 
            $this->client->getConfig()->getMerchantSecretKey(),
            $this->client->getConfig()->getHashAlgorithm()
        );
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'editPlan.do');
    }
}
