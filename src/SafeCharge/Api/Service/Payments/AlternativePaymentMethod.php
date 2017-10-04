<?php


namespace SafeCharge\Api\Service\Payments;


use SafeCharge\Api\RestClient;
use SafeCharge\Api\Service\BaseService;
use SafeCharge\Api\Utils;

class AlternativePaymentMethod extends BaseService
{
    /**
     * AlternativePaymentMethod constructor.
     * @param RestClient $client
     */
    public function __construct(RestClient $client)
    {
        parent::__construct($client);
    }

    /**
     * @param array $params
     * @return mixed
     * @link https://www.safecharge.com/docs/API/#getMerchantPaymentMethods
     */
    public function getMerchantPaymentMethods(array $params)
    {
        $mandatoryFields         = ['sessionToken', 'merchantId', 'merchantSiteId', 'timeStamp', 'checksum'];
        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->_client->getConfig()->getMerchantSecretKey(), $this->_client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getMerchantPaymentMethods.do');
    }

    /**
     * @param array $params
     * @return mixed
     * @link https://www.safecharge.com/docs/API/#paymentAPM
     */
    public function paymentAPM(array $params)
    {
        $mandatoryFields         = ['sessionToken', 'merchantId', 'merchantSiteId', 'currency', 'amount', 'paymentMethod', 'urlDetails', 'timeStamp', 'checksum'];
        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'amount', 'currency', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->_client->getConfig()->getMerchantSecretKey(), $this->_client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);
        return $this->requestJson($params, 'paymentAPM.do');
    }
}