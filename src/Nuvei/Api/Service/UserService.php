<?php

namespace Nuvei\Api\Service;

use Nuvei\Api\RestClient;
use Nuvei\Api\Utils;

/**
 * Class UserService
 * @package Nuvei\Api\Service
 */
class UserService extends BaseService
{

    /**
     * UsersManagement constructor.
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
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#createUser
     */
    public function createUser(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'countryCode',
            'email',
            'firstName',
            'lastName',
            'timeStamp',
            'checksum',
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'firstName',
            'lastName',
            'address',
            'state',
            'city',
            'zip',
            'countryCode',
            'phone',
            'locale',
            'email',
            'county',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());


        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'createUser.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#updateUser
     */
    public function updateUser(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'firstName',
            'lastName',
            'address',
            'state',
            'city',
            'zip',
            'countryCode',
            'phone',
            'locale',
            'email',
            'county',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());


        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'updateUser.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getUserDetails
     */
    public function getUserDetails(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getUserDetails.do');
    }
}
