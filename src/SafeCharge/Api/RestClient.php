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
    const CLIENT_VERSION = '2.0.0';

    const API_VERSION = "v1";

    private $config;
    private $httpClient;

    /** @var $logger LoggerInterface */
    private $logger;

    public static function getClientName()
    {
        return self::CLIENT_NAME . '-' . self::CLIENT_VERSION;
    }

    /**
     * RestClient constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = new Config($config);
    }

    /**
     * @return ConfigInterface | null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param ConfigInterface $config
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;
    }


    /**
     * @return string
     * @throws Exception\ConfigurationException
     */
    public function getApiUrl()
    {
        return $this->config->getEndpoint() . '/' . self::API_VERSION . '/';
    }


    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new HttpClient();
        }
        return $this->httpClient;
    }

    /**
     * Set the Logger object
     *
     * @param $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get The Logger Object
     */
    public function getLogger()
    {
        if ($this->logger == null) {
            $this->setLogger(new Logger());
        }
        return $this->logger;
    }
}
