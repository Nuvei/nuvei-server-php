<?php

namespace SafeCharge\Api;

use SafeCharge\Api\Interfaces\ConfigInterface;
use SafeCharge\Api\Interfaces\LoggerInterface;

/**
 * Class RestClient
 * @package SafeCharge\Api
 */
class RestClient
{

    const CLIENT_NAME    = 'safecharge-php-client';
    const CLIENT_VERSION = '1.0.6';

    const API_VERSION = "v1";

    private $_config;
    private $_httpClient;

    /** @var $_logger LoggerInterface */
    private $_logger;


    /**
     * RestClient constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->_config = new Config($config);
    }

    /**
     * @return ConfigInterface | null
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * @param ConfigInterface $config
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->_config = $config;
    }


    /**
     * @return string
     * @throws Exception\ConfigurationException
     */
    public function getApiUrl()
    {
        return $this->_config->getEndpoint() . '/' . self::API_VERSION . '/';
    }


    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (is_null($this->_httpClient)) {
            $this->_httpClient = new HttpClient();
        }
        return $this->_httpClient;
    }

    /**
     * Set the Logger object
     * @param $logger
     */
    public function setLogger($logger)
    {
        $this->_logger = $logger;
    }

    /**
     * Get The Logger Object
     */
    public function getLogger()
    {
        if ($this->_logger == null) {
            $this->setLogger(new Logger());
        }
        return $this->_logger;
    }

}