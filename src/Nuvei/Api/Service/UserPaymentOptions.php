<?php

namespace Nuvei\Api\Service;

use Nuvei\Api\RestClient;
use Nuvei\Api\Utils;

/**
 * Class UserPaymentOptions
 * @package Nuvei\Api\Service
 */
class UserPaymentOptions extends BaseService
{

    /**
     * UserPaymentOptions constructor.
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
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#addUPOCreditCard
     */
    public function addUPOCreditCard(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'ccCardNumber',
            'ccExpMonth',
            'ccExpYear',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'ccCardNumber',
            'ccExpMonth',
            'ccExpYear',
            'ccNameOnCard',
            'billingAddress',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'addUPOCreditCard.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#addUPOCreditCardByToken
     */
    public function addUPOCreditCardByToken(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            //'ccCardNumber',
            'ccExpMonth',
            'ccExpYear',
            'ccNameOnCard',
            'ccToken',
            'brand',
            'uniqueCC',
            'bin',
            'last4Digits',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'ccExpMonth',
            'ccExpYear',
            'ccNameOnCard',
            'ccToken',
            'brand',
            'uniqueCC',
            'bin',
            'last4Digits',
            'billingAddress',
            'timeStamp',
            'merchantSecretKey'
        ];


        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'addUPOCreditCardByToken.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#addUPOCreditCardByTempToken
     */
    public function addUPOCreditCardByTempToken(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'ccTempToken',
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

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'addUPOCreditCardByTempToken.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#addUPOAPM
     */
    public function addUPOAPM(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'paymentMethodName',
            'apmData',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'paymentMethodName',
            'apmData',
            'billingAddress',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'addUPOAPM.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#addUPOAPM
     */
    public function editUPOCC(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'userPaymentOptionId',
            'ccExpMonth',
            'ccExpYear',
            'ccNameOnCard',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'userPaymentOptionId',
            'ccExpMonth',
            'ccExpYear',
            'ccNameOnCard',
            'billingAddress',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'editUPOCC.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#editUPOAPM
     */
    public function editUPOAPM(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'userPaymentOptionId',
            'apmData',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'userPaymentOptionId',
            'apmData',
            'billingAddress',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'editUPOAPM.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#deleteUPO
     */
    public function deleteUPO(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'userPaymentOptionId',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'userPaymentOptionId',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'deleteUPO.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#getUserUPOs
     */
    public function getUserUPOs(array $params)
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

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getUserUPOs.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#suspendUPO
     */
    public function suspendUPO(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'userPaymentOptionId',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'userPaymentOptionId',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'suspendUPO.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/advanced/indexAdvanced.html?json#enableUPO
     */
    public function enableUPO(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'userPaymentOptionId',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'clientRequestId',
            'userPaymentOptionId',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'enableUPO.do');
    }
}
