<?php

namespace SafeCharge\Api\Service;

use SafeCharge\Api\Exception\ValidationException;
use SafeCharge\Api\Interfaces\ServiceInterface;
use SafeCharge\Api\RestClient;

/**
 * Class BaseService
 * @package SafeCharge\Api\Service
 */
class BaseService implements ServiceInterface
{
    /**
     * @var RestClient
     */
    protected $_client;
    /**
     * @var string
     */
    protected $_apiUrl;
    /**
     * @var
     */
    protected $_errors;


    /**
     * BaseService constructor.
     *
     * @param RestClient $client
     *
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     */
    public function __construct(RestClient $client)
    {
        $this->_client = $client;
        $this->_apiUrl = $this->_client->getApiUrl();
    }

    /**
     * @return RestClient
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * Check if merchantId, merchantSiteId and timeStamp parameters are given.
     * If they are not, we get them from the configuration and append them
     *
     * @param $params
     *
     * @return mixed
     */
    public function appendMerchantIdMerchantSiteIdTimeStamp($params)
    {
        if (empty($params['merchantId'])) {
            $params['merchantId'] = $this->_client->getConfig()->getMerchantId();
        }
        if (empty($params['merchantSiteId'])) {
            $params['merchantSiteId'] = $this->_client->getConfig()->getMerchantSiteId();
        }
        if (empty($params['timeStamp'])) {
            $params['timeStamp'] = date('YmdHms');
        }

        return $params;
    }

    /**
     * Check the mandatory fields
     *
     * @param $params
     * @param $mandatoryFields
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate($params, $mandatoryFields)
    {
        $missingFields = [];
        $arrayKeys     = array_keys($params);
        foreach ($mandatoryFields as $field) {
            if (!in_array($field, $arrayKeys)) {
                $missingFields[] = $field;
            }
        }
        if (!empty($missingFields)) {
            throw new ValidationException('Missing input parameters: ' . implode(',', $missingFields));
        }

        return true;
    }

    /**
     * @param $params
     * @param $endpoint
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     */
    public function requestJson($params, $endpoint)
    {
        $curlClient = $this->_client->getHttpClient();

        return $curlClient->requestJson($this, $this->_apiUrl . $endpoint, $params);
    }

    /**
     * @param $params
     * @param $endpoint
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\SafeChargeException
     */
    public function requestPost($params, $endpoint)
    {
        $curlClient = $this->_client->getHttpClient();

        return $curlClient->requestPost($this, $this->_apiUrl . $endpoint, $params);
    }


}