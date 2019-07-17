<?php


namespace SafeCharge\Api\Service;

use SafeCharge\Api\RestClient;
use SafeCharge\Api\Exception\ConfigurationException;
use SafeCharge\Api\Service\Payments\TransactionActions;
use SafeCharge\Api\Utils;

/**
 * Class PaymentService
 * @package SafeCharge\Api\Service
 */
class PaymentService extends BaseService
{

    /**
     * @var TransactionActions
     */
    protected $_transactionActionObject;


    /**
     * @return TransactionActions
     * @throws ConfigurationException
     */
    private function getTransactionActionObject()
    {
        if (is_null($this->_transactionActionObject)) {
            $this->_transactionActionObject = new TransactionActions($this->getClient());
        }
        return $this->_transactionActionObject;
    }

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
        $mandatoryFields = ['merchantId', 'merchantSiteId', 'timeStamp', 'checksum', 'currency', 'amount', 'paymentOption', 'billingAddress'];

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

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->_client->getConfig()->getMerchantSecretKey(), $this->_client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'payment.do');
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws ConfigurationException
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function voidTransaction(array $params)
    {
        return $this->getTransactionActionObject()->voidTransaction($params);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws ConfigurationException
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function refundTransaction(array $params)
    {
        return $this->getTransactionActionObject()->refundTransaction($params);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws ConfigurationException
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function settleTransaction(array $params)
    {
        return $this->getTransactionActionObject()->settleTransaction($params);
    }
}
