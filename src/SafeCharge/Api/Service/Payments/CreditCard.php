<?php

namespace SafeCharge\Api\Service\Payments;

use SafeCharge\Api\RestClient;
use SafeCharge\Api\Service\BaseService;
use SafeCharge\Api\Utils;

class CreditCard extends BaseService
{

    /**
     * CreditCard constructor.
     * @param RestClient $client
     */
    public function __construct(RestClient $client)
    {
        parent::__construct($client);
    }

    /**
     * @param array $params
     * @return mixed
     * @link https://www.safecharge.com/docs/API/#cardTokenization
     */
    public function cardTokenization(array $params)
    {
        $mandatoryFields = ['sessionToken', 'cardData'];

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'cardTokenization.do');
    }

    /**
     * @param array $params
     * @return mixed
     * @link https://www.safecharge.com/docs/API/#paymentCC
     */
    public function paymentCC(array $params)
    {
        $mandatoryFields = ['sessionToken', 'merchantId', 'merchantSiteId', 'transactionType', 'isRebilling', 'currency', 'amount', 'items', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'amount', 'currency', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->_client->getConfig()->getMerchantSecretKey(), $this->_client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'paymentCC.do');
    }


}