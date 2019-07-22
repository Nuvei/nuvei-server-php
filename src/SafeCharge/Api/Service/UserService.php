<?php

namespace SafeCharge\Api\Service;

use SafeCharge\Api\RestClient;
use SafeCharge\Api\Utils;

/**
 * Class UserService
 * @package SafeCharge\Api\Service
 */
class UserService extends BaseService
{

    /**
     * UsersManagement constructor.
     *
     * @param RestClient $client
     *
     * @throws \SafeCharge\Api\Exception\ConfigurationException
     */
    public function __construct(RestClient $client)
    {
        parent::__construct($client);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     * @link https://www.safecharge.com/docs/API/#createUser
     */
    public function createUser(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'userTokenId', 'countryCode', 'timeStamp', 'checksum'];

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
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     * @link https://www.safecharge.com/docs/API/#updateUser
     */
    public function updateUser(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'timeStamp', 'checksum'];

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
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     * @link https://www.safecharge.com/docs/API/#getUserDetails
     */
    public function getUserDetails(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'userTokenId', 'clientRequestId', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getUserDetails.do');
    }
}
