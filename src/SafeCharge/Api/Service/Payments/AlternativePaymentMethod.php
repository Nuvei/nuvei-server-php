<?php

namespace SafeCharge\Api\Service\Payments;

use SafeCharge\Api\RestClient;
use SafeCharge\Api\Service\BaseService;
use SafeCharge\Api\Utils;

/**
 * Class AlternativePaymentMethod
 * @package SafeCharge\Api\Service\Payments
 */
class AlternativePaymentMethod extends BaseService
{
    /**
     * AlternativePaymentMethod constructor.
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
     * @link https://www.safecharge.com/docs/API/#getMerchantPaymentMethods
     */
    public function getMerchantPaymentMethods(array $params)
    {
        $mandatoryFields         = ['sessionToken', 'merchantId', 'merchantSiteId', 'timeStamp', 'checksum'];
        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getMerchantPaymentMethods.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     * @link https://www.safecharge.com/docs/API/#paymentAPM
     */
    public function paymentAPM(array $params)
    {
        $mandatoryFields         = ['sessionToken', 'merchantId', 'merchantSiteId', 'currency', 'amount', 'paymentMethod', 'urlDetails', 'timeStamp', 'checksum'];
        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'amount', 'currency', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);
        return $this->requestJson($params, 'paymentAPM.do');
    }
}
