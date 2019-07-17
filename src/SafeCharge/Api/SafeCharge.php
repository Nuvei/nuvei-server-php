<?php


namespace SafeCharge\Api;

use SafeCharge\Api\Service\AuthenticationManagement;
use SafeCharge\Api\Service\BaseService;
use SafeCharge\Api\Service\PaymentService;
use SafeCharge\Api\Service\UserService;

class SafeCharge extends BaseService
{
    /**
     * @var RestClient
     */
    private $_client;

    /**
     * @var PaymentService
     */
    private $_paymentService;

    /**
     * @var UserService
     */
    private $_userService;

    /**
     * @var AuthenticationManagement
     */
    private $_authenticationService;

    /**
     * @param array $params
     */
    public function initialize(array $params)
    {
        $this->_client = new RestClient($params);
    }

    /**
     * Set the Logger object
     *
     * @param $logger
     */
    public function setLogger($logger)
    {
        $this->_client->setLogger($logger);
    }



    /**
     * @return mixed
     * @throws Exception\ConfigurationException
     * @throws Exception\ConnectionException
     * @throws Exception\ResponseException
     * @throws Exception\ValidationException
     */
    public function getSessionToken()
    {
        $sessionTokenResponse = $this->getAuthenticationService()->getSessionToken();
        return $sessionTokenResponse['sessionToken'];
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     * @link https://www.safecharge.com/docs/API/#openOrder
     */
    public function openOrder(array $params)
    {
        $mandatoryFields = ['sessionToken', 'merchantId', 'merchantSiteId', 'currency', 'amount', 'items', 'timeStamp', 'checksum'];

        $checksumParametersOrder = ['merchantId', 'merchantSiteId', 'clientRequestId', 'amount', 'currency', 'timeStamp', 'merchantSecretKey'];

        $params = $this->appendMerchantIdMerchantSiteIdTimeStamp($params);

        if (empty($params['checksum'])) {
            $params['checksum'] = Utils::calculateChecksum($params, $checksumParametersOrder, $this->_client->getConfig()->getMerchantSecretKey(), $this->_client->getConfig()->getHashAlgorithm());
        }

        $this->validate($params, $mandatoryFields);

        return $this->requestJson($params, 'openOrder.do');
    }

    /**
     * @return PaymentService
     * @throws Exception\ConfigurationException
     */
    public function getPaymentService()
    {
        if (is_null($this->_paymentService)) {
            $this->_paymentService = new PaymentService($this->_client);
        }
        return $this->_paymentService;
    }

    /**
     * @return UserService
     * @throws Exception\ConfigurationException
     */
    public function getUserService()
    {
        if (is_null($this->_userService)) {
            $this->_userService = new UserService($this->_client);
        }
        return $this->_userService;
    }

    /**
     * @return AuthenticationManagement
     * @throws Exception\ConfigurationException
     */
    public function getAuthenticationService()
    {
        if (is_null($this->_authenticationService)) {
            $this->_authenticationService = new AuthenticationManagement($this->_client);
        }
        return $this->_authenticationService;
    }
}
