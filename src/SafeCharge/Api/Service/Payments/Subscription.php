<?php


namespace SafeCharge\Api\Service\Payments;


use SafeCharge\Api\RestClient;
use SafeCharge\Api\Service\BaseService;
use SafeCharge\Api\Utils;

class Subscription extends BaseService
{
    /**
     * Subscription constructor.
     * @param RestClient $client
     */
    public function __construct(RestClient $client)
    {
        parent::__construct($client);
    }

    public function createSubscription(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'userTokenId', 'subscriptionPlanId', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'userTokenId', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->_client->getConfig()->getMerchantSecretKey(), $this->_client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'createSubscription.do');
    }

    public function cancelSubscription(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'userTokenId', 'subscriptionId', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->_client->getConfig()->getMerchantSecretKey(), $this->_client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'cancelSubscription.do');
    }

    public function getSubscriptionList(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'userTokenId', 'subscriptionId', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->_client->getConfig()->getMerchantSecretKey(), $this->_client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getSubscriptionList.do');
    }

    public function getSubscriptionPlans(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->_client->getConfig()->getMerchantSecretKey(), $this->_client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getSubscriptionPlans.do');
    }

}