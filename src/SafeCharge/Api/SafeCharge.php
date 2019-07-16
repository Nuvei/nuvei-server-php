<?php


namespace SafeCharge\Api;


use SafeCharge\Api\Service\AuthenticationManagement;
use SafeCharge\Api\Service\PaymentService;

class SafeCharge
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
