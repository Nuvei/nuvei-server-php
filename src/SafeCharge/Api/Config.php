<?php

namespace SafeCharge\Api;

use SafeCharge\Api\Exception\ConfigurationException;
use SafeCharge\Api\Interfaces\ConfigInterface;

/**
 * Class Config
 * @package SafeCharge\Api
 */
class Config implements ConfigInterface
{
    const ENDPOINT_LIVE = "https://secure.safecharge.com/ppp/api";
    const ENDPOINT_TEST = "https://ppp-test.safecharge.com/ppp/api";


    protected $configData = [];

    protected $allowedHashAlgorithms = ['sha256', 'md5'];

    // allowed output
    protected $allowedOutput = ['array', 'json'];
    protected $defaultOutput = 'array';

    /**
     * Config constructor.
     *
     * @param bool|array $params
     */
    public function __construct($params = false)
    {
        if ($params && is_array($params)) {
            foreach ($params as $key => $param) {
                $methodName = 'set' . ucfirst($key);
                if (method_exists($this, $methodName)) {
                    call_user_func([$this, $methodName], $param);
                } else {
                    $this->configData[$key] = $param;
                }
            }
        }
        return $this;
    }

    /**
     * Get a specific key value.
     *
     * @param string $key Key to retrieve.
     *
     * @return mixed|null Value of the key or NULL
     */
    public function get($key)
    {
        return isset($this->configData[$key]) ? $this->configData[$key] : null;
    }

    /**
     * Set a key value pair
     *
     * @param string $key Key to set
     * @param mixed $value Value to set
     */
    public function set($key, $value)
    {
        $this->configData[$key] = $value;
    }

    /**
     * Type can be json or array
     *
     * @param $value
     *
     * @return $this
     */
    public function setOutputType($value)
    {
        $this->set('outputType', $value);
        return $this;
    }

    public function getOutputType()
    {
        if (isset($this->configData['outputType']) && in_array($this->configData['outputType'], $this->allowedOutput)) {
            return $this->configData['outputType'];
        }
        // return the default type
        return $this->defaultOutput;
    }

    /**
     * @param $environment
     *
     * @return $this
     * @throws ConfigurationException
     */
    public function setEnvironment($environment)
    {
        if ($environment == Environment::TEST) {
            $this->set('environment', Environment::TEST);
            $this->set('endpoint', self::ENDPOINT_TEST);
        } elseif ($environment == Environment::LIVE) {
            $this->set('environment', Environment::LIVE);
            $this->set('endpoint', self::ENDPOINT_LIVE);
        } else {
            // environment does not exists
            $msg = "This environment does not exists use " . Environment::TEST . ' or ' . Environment::LIVE;
            throw new ConfigurationException($msg);
        }
        return $this;
    }

    /**
     * @return string environment
     * @throws ConfigurationException
     */
    public function getEnvironment()
    {
        if (!isset($this->configData['environment'])) {
            throw new ConfigurationException('environment is not configured');
        }
        return $this->configData['environment'];
    }

    /**
     * @return string environment
     * @throws ConfigurationException
     */
    public function getEndpoint()
    {
        if (!isset($this->configData['environment'])) {
            throw new ConfigurationException('environment is not configured');
        }

        if (!isset($this->configData['endpoint'])) {
            throw new ConfigurationException('endpoint is not configured');
        }
        return $this->configData['endpoint'];
    }

    /**
     * @param $merchantSiteId
     *
     * @return $this
     */
    public function setMerchantSiteId($merchantSiteId)
    {
        $this->set('merchantSiteId', $merchantSiteId);
        return $this;
    }

    /**
     * @return string merchantSiteId
     * @throws ConfigurationException
     */
    public function getMerchantSiteId()
    {
        if (!isset($this->configData['merchantSiteId'])) {
            throw new ConfigurationException('merchantSiteId is not configured');
        }
        return $this->configData['merchantSiteId'];
    }

    /**
     * @param $merchantId
     *
     * @return $this
     */
    public function setMerchantId($merchantId)
    {
        $this->set('merchantId', $merchantId);
        return $this;
    }

    /**
     * @return int merchantId
     * @throws ConfigurationException
     */
    public function getMerchantId()
    {
        if (!isset($this->configData['merchantId'])) {
            throw new ConfigurationException('merchantId is not configured');
        }
        return $this->configData['merchantId'];
    }


    /**
     * @param $merchantSecretKey
     *
     * @return $this
     */
    public function setMerchantSecretKey($merchantSecretKey)
    {
        $this->set('merchantSecretKey', $merchantSecretKey);
        return $this;
    }


    /**
     * @return string merchantSecretKey
     * @throws ConfigurationException
     */
    public function getMerchantSecretKey()
    {
        if (!isset($this->configData['merchantSecretKey'])) {
            throw new ConfigurationException('merchantSecretKey is not configured');
        }
        return $this->configData['merchantSecretKey'];
    }

    /**
     * @param $hashAlgorithm
     *
     * @return $this
     * @throws ConfigurationException
     */
    public function setHashAlgorithm($hashAlgorithm)
    {
        if (!in_array($hashAlgorithm, $this->allowedHashAlgorithms)) {
            throw new ConfigurationException('hashAlgorithm ' . $hashAlgorithm . ' is not supported. Please use ' . implode(', ', $this->allowedHashAlgorithms) . ' .');
        }
        $this->set('hashAlgorithm', $hashAlgorithm);
        return $this;
    }

    /**
     * @return string hashAlgorithm
     * @throws ConfigurationException
     */
    public function getHashAlgorithm()
    {
        if (!isset($this->configData['hashAlgorithm'])) {
            $this->setHashAlgorithm('sha256');
        }
        return $this->configData['hashAlgorithm'];
    }
}
