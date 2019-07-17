<?php

namespace SafeCharge\Api;

use Psr\Log\LoggerInterface;
use SafeCharge\Api\Exception\ConnectionException;
use SafeCharge\Api\Exception\ResponseException;
use SafeCharge\Api\Exception\SafeChargeException;
use SafeCharge\Api\Interfaces\HttpClientInterface;
use SafeCharge\Api\Interfaces\ServiceInterface;

/**
 * Class HttpClient
 * @package SafeCharge\Api
 */
class HttpClient implements HttpClientInterface
{
    /**
     * Json API request to SafeCharge
     *
     * @param ServiceInterface $service
     * @param $requestUrl
     * @param $params
     *
     * @return mixed
     * @throws ConnectionException
     * @throws ResponseException
     */
    public function requestJson(ServiceInterface $service, $requestUrl, $params)
    {
        /** @var RestClient $client */
        $client = $service->getClient();
        $config = $client->getConfig();
        $logger = $client->getLogger();

        $jsonRequest = json_encode($params);

        // log the requestUr, params and json request

        $logger->info("[SafeCharge PHP SDK] Request url: " . $requestUrl);
        $logger->info('[SafeCharge PHP SDK] Request:' . $jsonRequest . PHP_EOL);

        //Initiate cURL.
        $ch = curl_init($requestUrl);
        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
        // set authorisation
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //Attach our encoded JSON string to the POST fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonRequest);

        $headers = [
            'Content-Type: application/json',
            'Client-Name: ' . RestClient::CLIENT_NAME,
            'Client-Version: ' . RestClient::CLIENT_VERSION
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // return the result
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //Execute the request
        $result     = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // result not 200 throw error
        if ($result) {
            $logger->info('[SafeCharge PHP SDK] Response:' . $result . PHP_EOL);
            $this->handleResultError($result, $logger);
        } elseif (!$result) {
            $errno   = curl_errno($ch);
            $message = curl_error($ch);
            curl_close($ch);
            $this->handleCurlError($requestUrl, $errno, $message, $logger);
        }
        curl_close($ch);
        // result in array or json
        if ($config->getOutputType() == 'array') {
            // transform to PHP Array
            $result = json_decode($result, true);
        }

        return $result;
    }

    /**
     * Request to SafeCharge with query string used for Directory Lookup
     *
     * @param ServiceInterface $service
     * @param $requestUrl
     * @param $params
     *
     * @return mixed
     * @throws ConnectionException
     * @throws ResponseException
     * @throws SafeChargeException
     */
    public function requestPost(ServiceInterface $service, $requestUrl, $params)
    {
        /** @var RestClient $client */
        $client = $service->getClient();
        $config = $client->getConfig();
        $logger = $client->getLogger();

        // log the requestUr, params and json request
        $logger->info("Request url: " . $requestUrl);
        $logger->info('Params:' . print_r($params, true));


        //Initiate cURL.
        $ch = curl_init($requestUrl);
        //Tell cURL that we want to send a POST request.
        curl_setopt($ch, CURLOPT_POST, 1);
        // set authorisation
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        $headers = [
            'Content-Type: application/json',
            'Client-Name: ' . RestClient::CLIENT_NAME,
            'Client-Version: ' . RestClient::CLIENT_VERSION
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // return the result
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //Execute the request
        $result     = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($result) {
            $logger->info('[SafeCharge PHP SDK] Response:' . $result);
            $this->handleResultError($result, $logger);
        } elseif (!$result) {
            $errno      = curl_errno($ch);
            $message    = curl_error($ch);
            $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $this->handleCurlError($requestUrl, $errno, $message, $logger);
        }
        curl_close($ch);
        // result in array or json
        if ($config->getOutputType() == 'array') {
            // transform to PHP Array
            $result = json_decode($result, true);
            if (!$result) {
                $msg = "The result is empty, looks like your request is invalid";
                $logger->error('[SafeCharge PHP SDK] ' . $msg);

                throw new SafeChargeException($msg);
            }
        }
        return $result;
    }

    /**
     * Handle Curl exceptions
     *
     * @param $url
     * @param $errno
     * @param $message
     * @param $logger
     *
     * @throws ConnectionException
     */
    protected function handleCurlError($url, $errno, $message, $logger)
    {
        switch ($errno) {
            case CURLE_OK:
                $msg = "Probably your credentials are incorrect";
                break;
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to SafeCharge ($url).  Please check your "
                    . "internet connection and try again.";
                break;
            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = "Could not verify SafeCharge's SSL certificate.  Please make sure "
                    . "that your network is not intercepting certificates.  "
                    . "(Try going to $url in your browser.)  "
                    . "If this problem persists, contact us at support@safecharge.com";
                break;
            default:
                $msg = "Unexpected error communicating with SafeCharge.";
        }
        $msg .= PHP_EOL . "[SafeCharge PHP SDK] (Network error [errno $errno]: $message)";
        $logger->error($msg);
        throw new ConnectionException($msg);
    }

    /**
     * Handle result errors from SafeCharge
     *
     * @param $result
     * @param $logger
     *
     * @throws ResponseException
     */
    protected function handleResultError($result, $logger)
    {
        $decodedResult = json_decode($result, true);
        if (isset($decodedResult['errCode']) && !empty($decodedResult['errCode'])) {
            $logger->error("[SafeCharge PHP SDK] " . $decodedResult['errCode'] . ': ' . $decodedResult['reason']);
            throw new ResponseException($decodedResult['reason'], $decodedResult['errCode'], null, null, $decodedResult);
        } elseif (isset($decodedResult['gwErrorCode']) && !empty($decodedResult['gwErrorCode'])) {
            $logger->error("[SafeCharge PHP SDK] " . $decodedResult['gwErrorCode'] . ': ' . $decodedResult['gwErrorReason']);
            throw new ResponseException($decodedResult['gwErrorReason'], $decodedResult['gwErrorCode'], null, null, $decodedResult);
        }
    }
}
