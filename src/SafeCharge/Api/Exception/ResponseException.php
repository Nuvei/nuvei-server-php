<?php

namespace SafeCharge\Api\Exception;

use Exception;

/**
 * Class ResponseException
 * @package SafeCharge\Api\Exception
 */
class ResponseException extends Exception
{
    protected $_status;
    protected $_data;

    /**
     * ResponseException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     * @param null $status
     * @param null $data
     */
    public function __construct($message = "", $code = 0, Exception $previous = null, $status = null, $data = null)
    {
        $this->_status = $status;
        $this->_data   = $data;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get status
     *
     * @return null
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * Get response data
     *
     * @return null
     */
    public function getData()
    {
        return $this->_data;
    }
}
