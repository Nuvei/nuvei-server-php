<?php


namespace Nuvei\Api\Service;

use Nuvei\Api\RestClient;
use Nuvei\Api\Exception\ConfigurationException;
use Nuvei\Api\Utils;

/**
 * Class PaymentService
 * @package Nuvei\Api\Service
 */
class PaymentService extends BaseService
{

    /**
     * PaymentService constructor.
     *
     * @param RestClient $client
     *
     * @throws ConfigurationException
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
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#payment
     */
    public function createPayment(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'sessionToken',
            'currency',
            'amount',
            'paymentOption',
            'timeStamp',
            'checksum',
            'deviceDetails',
            'billingAddress'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'amount',
            'currency',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $params = $this->appendIpAddress($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        if(!isset($params['sessionToken'])) {
            $params['sessionToken'] = $this->getSessionToken();
        }

        if (isset($params['relatedTransactionId'])) {
            unset($params['externalSchemeDetails']['transactionId']);
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'payment.do');
    }


    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#initPayment
     */
    public function initPayment(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'currency',
            'amount',
            'paymentOption',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'amount',
            'currency',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $params = $this->appendIpAddress($params);

        $params['checksum']     = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        if(!isset($params['sessionToken'])) {
            $params['sessionToken'] = $this->getSessionToken();
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'initPayment.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#openOrder
     */
    public function openOrder(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'timeStamp',
            'checksum',
            'currency',
            'amount',
            'sessionToken'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'amount',
            'currency',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $params = $this->appendIpAddress($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        if(!isset($params['sessionToken'])) {
            $params['sessionToken'] = $this->getSessionToken();
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'openOrder.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#settleTransaction
     */
    public function settleTransaction(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'amount',
            'currency',
            'relatedTransactionId',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'clientUniqueId',
            'amount',
            'currency',
            'relatedTransactionId',
            'authCode',
            'descriptorMerchantName',
            'descriptorMerchantPhone',
            'comment',
            'urlDetails',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'settleTransaction.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#refundTransaction
     */
    public function refundTransaction(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'amount',
            'currency',
            'timeStamp',
            'checksum',
            'relatedTransactionId',
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'clientUniqueId',
            'amount',
            'currency',
            'relatedTransactionId',
            'authCode',
            'comment',
            'urlDetails',
            'timeStamp',
            'merchantSecretKey'
        ];


        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'refundTransaction.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#voidTransaction
     */
    public function voidTransaction(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'clientUniqueId',
            'amount',
            'currency',
            'relatedTransactionId',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'clientUniqueId',
            'amount',
            'currency',
            'relatedTransactionId',
            'authCode',
            'comment',
            'urlDetails',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'voidTransaction.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#getPaymentStatus
     */
    public function getPaymentStatus(array $params)
    {
        $mandatoryFields = [
            'sessionToken'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getPaymentStatus.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#accountCaptureAPI
     */
    public function accountCapture(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'userTokenId',
            'paymentMethod',
            'currencyCode',
            'countryCode',
            'amount',
            'countryCode'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if(!isset($params['sessionToken'])) {
            $params['sessionToken'] = $this->getSessionToken();
        }
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'accountCapture.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#getCardDetailsAPI
     */
    public function getCardDetails(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'cardNumber'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if(!isset($params['sessionToken'])) {
            $params['sessionToken'] = $this->getSessionToken();
        }
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getCardDetails.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#getMcpRates
     */
    public function getMcpRates(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'fromCurrency'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if(!isset($params['sessionToken'])) {
            $params['sessionToken'] = $this->getSessionToken();
        }
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getMcpRates.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#getDccDetails
     */
    public function getDccDetails(array $params)
    {
        $mandatoryFields = [
            'sessionToken',
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'clientUniqueId',
            'originalAmount',
            'originalCurrency'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        if(!isset($params['sessionToken'])) {
            $params['sessionToken'] = $this->getSessionToken();
        }
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'getDccDetails.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#authorize3d
     */
    public function authorize3d(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'sessionToken',
            'currency',
            'amount',
            'paymentOption',
            'relatedTransactionId',
            'deviceDetails',
            'billingAddress',
            'timeStamp',
            'checksum'
        ];

        $checksumParametersOrder = [
            'merchantId',
            'merchantSiteId',
            'clientRequestId',
            'amount',
            'currency',
            'timeStamp',
            'merchantSecretKey'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);
        $params = $this->appendIpAddress($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        if(!isset($params['sessionToken'])) {
            $params['sessionToken'] = $this->getSessionToken();
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'authorize3d.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \Nuvei\Api\Exception\ConnectionException
     * @throws \Nuvei\Api\Exception\ResponseException
     * @throws \Nuvei\Api\Exception\ValidationException
     * @link https://docs.nuvei.com/api/main/indexMain_v1_0.html?json#verify3d
     */
    public function verify3d(array $params)
    {
        $mandatoryFields = [
            'merchantId',
            'merchantSiteId',
            'sessionToken',
            'currency',
            'amount',
            'paymentOption',
            'relatedTransactionId',
            'billingAddress'
        ];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if(!isset($params['sessionToken'])) {
            $params['sessionToken'] = $this->getSessionToken();
        }
        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'verify3d.do');
    }
}
