<?php


namespace SafeCharge\Api\Interfaces;

/**
 * Interface ConfigInterface
 * @package SafeCharge\Api\Interfaces
 */
interface ConfigInterface
{
    /**
     * ConfigInterface constructor.
     *
     * @param bool $params
     *
     * @return $this
     */
    public function __construct($params = false);

    public function get($key);

    public function set($key, $value);

    /**
     * @param $value
     *
     * @return $this
     */
    public function setOutputType($value);

    public function getOutputType();

    /**
     * @param $environment
     *
     * @return $this
     */
    public function setEnvironment($environment);

    public function getEnvironment();

    public function getEndpoint();

    /**
     * @param $merchantSiteId
     *
     * @return $this
     */
    public function setMerchantSiteId($merchantSiteId);

    public function getMerchantSiteId();

    /**
     * @param $merchantId
     *
     * @return $this
     */
    public function setMerchantId($merchantId);

    public function getMerchantId();

    /**
     * @param $merchantSecretKey
     *
     * @return $this
     */
    public function setMerchantSecretKey($merchantSecretKey);

    public function getMerchantSecretKey();

    /**
     * @param $hashAlgorithm
     *
     * @return $this
     */
    public function setHashAlgorithm($hashAlgorithm);

    public function getHashAlgorithm();
}
