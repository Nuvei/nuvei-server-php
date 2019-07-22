<?php

namespace SafeCharge\Api\Service;

use SafeCharge\Api\Exception\ValidationException;
use SafeCharge\Api\Interfaces\ServiceInterface;
use SafeCharge\Api\RestClient;
use SafeCharge\Api\Utils;

/**
 * Class BaseService
 * @package SafeCharge\Api\Service
 */
class BaseService implements ServiceInterface
{
    /**
     * @var RestClient
     */
    protected $client;
    /**
     * @var string
     */
    protected $apiUrl;

    private $sessionToken;

    /**
     * BaseService constructor.
     *
     * @param RestClient $client
     *
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     */
    public function __construct(RestClient $client)
    {
        $this->client = $client;
        $this->apiUrl = $this->client->getApiUrl();
    }

    /**
     * @return RestClient
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return mixed
     * @throws ValidationException
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     */
    public function getSessionToken()
    {
        $mandatoryFields = ['merchantId', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp();

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());

        $this->validate($params, $mandatoryFields);

        $sessionTokenResponse = $this->requestJson($params, 'getSessionToken.do');

        $this->sessionToken = $sessionTokenResponse['sessionToken'];

        return $this->sessionToken;
    }

    /**
     * Check if merchantId, merchantSiteId and timeStamp parameters are given.
     * If they are not, we get them from the configuration and append them
     *
     * @param $params
     *
     * @return mixed
     */
    public function appendMerchantIdMerchantSiteIdTimeStamp($params = [])
    {
        if (empty($params['merchantId'])) {
            $params['merchantId'] = $this->client->getConfig()->getMerchantId();
        }
        if (empty($params['merchantSiteId'])) {
            $params['merchantSiteId'] = $this->client->getConfig()->getMerchantSiteId();
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
        $curlClient = $this->client->getHttpClient();

        return $curlClient->requestJson($this, $this->apiUrl . $endpoint, $params);
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
        $curlClient = $this->client->getHttpClient();

        return $curlClient->requestPost($this, $this->apiUrl . $endpoint, $params);
    }
}
