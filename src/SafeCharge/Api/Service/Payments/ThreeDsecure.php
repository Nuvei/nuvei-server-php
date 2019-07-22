<?php

namespace SafeCharge\Api\Service\Payments;

use SafeCharge\Api\RestClient;
use SafeCharge\Api\Service\BaseService;
use SafeCharge\Api\Utils;

/**
 * Class ThreeDsecure
 * @package SafeCharge\Api\Service\Payments
 */
class ThreeDsecure extends BaseService
{

    /**
     * ThreeDsecure constructor.
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
     * @link https://www.safecharge.com/docs/API/#dynamic3D
     */
    public function dynamic3D(array $params)
    {
        $mandatoryFields = ['sessionToken', 'merchantId', 'merchantSiteId', 'currency', 'amount', 'items', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'amount', 'currency', 'timeStamp', 'merchantSecretKey'];

        $params['webMasterId'] = RestClient::getClientName();

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
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     * @link https://www.safecharge.com/docs/API/#payment3D
     */
    public function payment3D(array $params)
    {
        $mandatoryFields = ['sessionToken', 'merchantId', 'merchantSiteId', 'transactionType', 'currency', 'amount', 'items', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'amount', 'currency', 'timeStamp', 'merchantSecretKey'];

        $params['webMasterId'] = RestClient::getClientName();

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);
        return $this->requestJson($params, 'payment3D.do');
    }
}
