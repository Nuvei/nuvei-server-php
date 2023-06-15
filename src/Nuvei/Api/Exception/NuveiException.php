<?php

namespace Nuvei\Api\Exception;

use Exception;

/**
 * Class NuveiException
 * @package Nuvei\Api\Exception
 */
class NuveiException extends Exception
{
    protected $_status;

    /**
     * NuveiException constructor.
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
