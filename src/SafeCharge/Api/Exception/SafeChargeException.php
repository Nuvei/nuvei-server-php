<?php

namespace SafeCharge\Api\Exception;

use Exception;

/**
 * Class SafeChargeException
 * @package SafeCharge\Api\Exception
 */
class SafeChargeException extends Exception
{
    protected $_status;

    /**
     * SafeChargeException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     * @param null $status
     */
    public function __construct($message = "", $code = 0, Exception $previous = null, $status = null)
    {
        $this->_status = $status;
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
}
