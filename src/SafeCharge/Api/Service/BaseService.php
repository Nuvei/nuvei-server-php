<?php

namespace SafeCharge\Api\Service;

use SafeCharge\Api\Exception\ValidationException;
use SafeCharge\Api\Interfaces\ServiceInterface;
use SafeCharge\Api\RestClient;
use SafeCharge\Api\Environment;
use SafeCharge\Api\Exception\SafeChargeException;

class BaseService implements ServiceInterface
{
    protected $_client;
    protected $_apiUrl;
    protected $_errors;

    /**
     * BaseService constructor.
     * @param RestClient $client
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
     * @param $params
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
     * @return bool
     * @throws ValidationException
     */
    public function validate($params, $mandatoryFields)
    {
        $missingFields = false;
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
     * @return mixed
     */
    public function requestJson($params, $endpoint)
    {
        $curlClient = $this->_client->getHttpClient();

        return $curlClient->requestJson($this, $this->_apiUrl . $endpoint, $params);
    }

    /**
     * @param $params
     * @param $endpoint
     * @return mixed
     */
    public function requestPost($params, $endpoint)
    {
        $curlClient = $this->_client->getHttpClient();

        return $curlClient->requestPost($this, $this->_apiUrl . $endpoint, $params);
    }


}