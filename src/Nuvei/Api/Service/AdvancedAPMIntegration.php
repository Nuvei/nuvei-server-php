<?php

namespace Nuvei\Api\Service;

use Nuvei\Api\RestClient;
use Nuvei\Api\Utils;

/**
 * Class AdvancedAPMIntegration
 * @package Nuvei\Api\Service
 */
class AdvancedAPMIntegration extends BaseService
{

    /**
     * AdvancedAPMIntegration constructor.
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
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#addBankAccount
     */
    public function addBankAccount(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'clientRequestId',
            'paymentOption' =>[
                'alternativePaymentMethod' => [
                    'paymentMethod'
                ],
            ],
            'userId',
            'bankAccount' => [
                'accountNumber',
                'routingNumber'
            ],
            'userTokenId',
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'addBankAccount.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#enrollAccount
     */
    public function enrollAccount(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'clientRequestId',
            'paymentOption' =>[
                'alternativePaymentMethod' => [
                    'paymentMethod'
                ],
            ],
            'userId',
            'userTokenId',
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'enrollAccount.do');
    }
    
    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#fundAccount
     */
    public function fundAccount(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'clientRequestId',
            'paymentOption' =>[
                'alternativePaymentMethod' => [
                    'paymentMethod'
                ],
            ],
            'userId',
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'fundAccount.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getAccountDetails
     */
    public function getAccountDetails(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'clientRequestId',
            'paymentOption' =>[
                'alternativePaymentMethod' => [
                    'paymentMethod'
                ],
            ],
            'userId',
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getAccountDetails.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getDocumentUrl
     */
    public function getDocumentUrl(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'clientRequestId',
            'paymentOption' =>[
                'alternativePaymentMethod' => [
                    'paymentMethod'
                ],
            ],
            'documentType',
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getDocumentUrl.do');
    }
}
