<?php

namespace Nuvei\Api\Interfaces;

/**
 * Interface HttpClientInterface
 * @package Nuvei\Api\Interfaces
 */
interface HttpClientInterface
{
    /**
     * @param ServiceInterface $service
     * @param $requestUrl
     * @param $params
     *
     * @return mixed
     */
    public function requestJson(ServiceInterface $service, $requestUrl, $params);

    public function requestPost(ServiceInterface $service, $requestUrl, $params);
}
