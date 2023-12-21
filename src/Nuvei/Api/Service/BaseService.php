<?php

namespace Nuvei\Api\Service;

use Nuvei\Api\Exception\ValidationException;
use Nuvei\Api\Interfaces\ServiceInterface;
use Nuvei\Api\RestClient;
use Nuvei\Api\Utils;

/**
 * Class BaseService
 * @package Nuvei\Api\Service
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
     * @throws \Nuvei\Api\Exception\ConfigurationException
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
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @link http://srv-bsf-devpppjs.gw-4u.com:4567/indexMain_v1_0.html?json#getSessionToken
     */
    public function getSessionToken()
    {
        if(!empty($this->sessionToken)) {
            return $this->sessionToken;
        }

        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
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
        if (empty($params['merchantSiteId'])) {
            $params = ['merchantSiteId' => $this->client->getConfig()->getMerchantSiteId()] + $params;
        }
        if (empty($params['merchantId'])) {
            $params = ['merchantId' => $this->client->getConfig()->getMerchantId()] + $params;
        }
        if (empty($params['timeStamp'])) {
            $params['timeStamp'] = date('YmdHms');
        }

        return $params;
    }

    /**
     * Check if ipAddress parameter is given.
     * If it is not, we get it and append it
     *
     * @param $params
     *
     * @return mixed
     */
    public function appendIpAddress($params = [])
    {
        if (!isset($params['deviceDetails']['ipAddress']) && isset($_SERVER['REMOTE_ADDR'])) {
            $params['deviceDetails']['ipAddress'] = $_SERVER['REMOTE_ADDR'];
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
        foreach ($mandatoryFields as $fieldKey => $fieldValue) {
            if(is_array($fieldValue)) {
                if(!isset($params[$fieldKey])) {
                    $missingFields[] = $fieldKey;
                    throw new ValidationException('Missing input parameters: ' . implode(',', $missingFields));
                } else {
                    $this->validate($params[$fieldKey], $fieldValue);
                }
            } else {
                if (!in_array($fieldValue, $arrayKeys)) {
                    $missingFields[] = $fieldValue;
                }
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
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     */
    public function requestJson($params, $endpoint, $debugg = false)
    {
        $service = $this;
        $client = $service->getClient();
        $config = $client->getConfig();
        $curlClient = $this->client->getHttpClient();
        $debug = $config->isDebugMode();

        $params['sourceApplication'] = Utils::getSourceApplication();
        $params['webMasterId'] = Utils::getWebMasterID();

        if($debug) {
            // echo "\nMethod: " . $endpoint . "\nRequest: ";
            // print_r($params);
        }

        if($debugg) {
        // return $params;
        //  return time();   // return $params;
        }
        $response = $curlClient->requestJson($this, $this->apiUrl . $endpoint, $params);

        if($debug) {
            // echo "Response: ";
            // print_r($response);
        }

        return $response;
    }

    /**
     * @param $params
     * @param $endpoint
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\NuveiException
     */
    public function requestPost($params, $endpoint)
    {
        $curlClient = $this->client->getHttpClient();

        return $curlClient->requestPost($this, $this->apiUrl . $endpoint, $params);
    }

    protected function call($params, $mandatoryFields, $endpoint, $checksumParametersOrder = null, $processAdditionalParams = false, $debugChecksumParams = false)
    {
        if (!$checksumParametersOrder && $processAdditionalParams) {
            $paramKeys = array_keys($params);
            $checksumParametersOrder = [
                'merchantId',
                'merchantSiteId',
                ...$paramKeys,
                'timeStamp',
                'checksum'
            ];
        }
        if (!$checksumParametersOrder) {
            $checksumParametersOrder = $mandatoryFields;
        }
        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum'] = Utils::calculateChecksum(
            $params,
            $checksumParametersOrder,
            $this->client->getConfig()->getMerchantSecretKey(),
            $this->client->getConfig()->getHashAlgorithm()
        );
        if($debugChecksumParams) {
            $checksumParams = [];
            foreach ($checksumParametersOrder as $value) {
                if (isset($params[$value])) {
                    $checksumParams[$value] = $params[$value];
                }
            }
        $checksumParams = Utils::arrayToString($checksumParams, ' - ');
            // $params['concatenatkdString'] = $checksumParams;
            //  return json_encode($params);die;
        }
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, $endpoint, $debugChecksumParams);
    }
}
