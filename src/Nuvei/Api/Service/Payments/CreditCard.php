<?php

namespace Nuvei\Api\Service\Payments;

use Nuvei\Api\RestClient;
use Nuvei\Api\Service\BaseService;
use Nuvei\Api\Utils;

/**
 * Class CreditCard
 * @package Nuvei\Api\Service\Payments
 */
class CreditCard extends BaseService
{

    /**
     * CreditCard constructor.
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
     * @link https://docs.nuvei.com/api/deprecated/indexDeprecated.html?json#cardTokenization
     */
    public function cardTokenization(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'cardData'
        ];

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'cardTokenization.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     */
    public function paymentCC(array $params)
    {
        $mandatoryFields = ['sessionToken', 'merchantId', 'merchantSiteId', 'transactionType', 'isRebilling', 'currency', 'amount', 'amountDetails', 'items', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'amount', 'currency', 'timeStamp', 'merchantSecretKey'];


        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'paymentCC.do');
    }
}
