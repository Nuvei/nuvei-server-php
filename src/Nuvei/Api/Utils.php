<?php

namespace Nuvei\Api;

/**
 * Class Utils
 * @package Nuvei\Api
 */
class Utils
{
    /**
     * @param $params array - parameters
     * @param $checksumParamsOrder array - array with parameter order for checksum calculation
     * @param $merchantSecretId - Merchant Site ID
     * @param string $algo - algorithm (sha256)
     *
     * @return string - checksum
     */
    public static function calculateChecksum(array $params, array $checksumParamsOrder, $merchantSecretId, $algo = 'sha256')
    {
        $checksumParams = [];
        foreach ($checksumParamsOrder as $value) {
            if (isset($params[$value])) {
                $checksumParams[$value] = $params[$value];
            }
        }
        $concatenatedString = self::arrayToString($checksumParams);
        return hash($algo, $concatenatedString . $merchantSecretId);
    }

    /**
     * @param $array
     *
     * @return string
     */
    public static function arrayToString($array, $test = '')
    {
        $string = '';
        foreach ($array as $element) {
            if (!is_array($element)) {
                $string .= $test.$element;
            } else {
                $string .= $test.self::arrayToString($element);
            }
        }
        return $string;
    }

    /**
     * @param $element
     * @param $array
     */
    public static function removeElementFromArray($element, &$array)
    {
        $index = array_search($element, $array);
        if ($index !== false) {
            unset($array[$index]);
        }
    }

    /**
     * @return string
     */
    public static function getSourceApplication()
    {
        return 'PHP_SDK';
    }

    /**
     * @return string
     */
    public static function getWebMasterID()
    {
        return PHP_VERSION;
    }
}
