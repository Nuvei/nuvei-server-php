<?php


namespace SafeCharge\Api\Service;

use SafeCharge\Api\RestClient;
use SafeCharge\Api\Exception\ConfigurationException;
use SafeCharge\Api\Utils;

/**
 * Class PaymentService
 * @package SafeCharge\Api\Service
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
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function createPayment(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'sessionToken', 'timeStamp', 'checksum', 'currency', 'amount', 'paymentOption', 'billingAddress', 'deviceDetails'];

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

        $params['checksum']     = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        $params['sessionToken'] = $this->getSessionToken();

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'payment.do');
    }


    /**
     * @param array $params
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function initPayment(array $params)
    {
        $mandatoryFields = ['sessionToken', 'merchantId', 'merchantSiteId', 'currency', 'amount', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'amount', 'currency', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum']     = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        $params['sessionToken'] = $this->getSessionToken();


        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'initPayment.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function openOrder(array $params)
    {
        $mandatoryFields = ['sessionToken', 'merchantId', 'merchantSiteId', 'currency', 'amount', 'items', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'amount', 'currency', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum']     = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());
        $params['sessionToken'] = $this->getSessionToken();


        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'openOrder.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     * @link https://www.safecharge.com/docs/API/#settleTransaction
     */
    public function settleTransaction(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'currency', 'amount', 'relatedTransactionId', 'authCode', 'timeStamp', 'checksum'];

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

        $params['webMasterId'] = RestClient::getClientName();

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
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     * @link https://www.safecharge.com/docs/API/#refundTransaction
     */
    public function refundTransaction(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'currency', 'amount', 'relatedTransactionId', 'authCode', 'timeStamp', 'checksum'];

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

        $params['webMasterId'] = RestClient::getClientName();

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'refundTransaction.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     * @link https://www.safecharge.com/docs/API/#voidTransaction
     */
    public function voidTransaction(array $params)
    {
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'currency', 'amount', 'relatedTransactionId', 'authCode', 'timeStamp', 'checksum'];

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

        $params['webMasterId'] = RestClient::getClientName();

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->client->getConfig()->getMerchantSecretKey(), $this->client->getConfig()->getHashAlgorithm());

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'voidTransaction.do');
    }
}
