<?php

namespace Nuvei\Api\Interfaces;

use Nuvei\Api\RestClient;

/**
 * Interface ServiceInterface
 * @package Nuvei\Api\Interfaces
 */
interface ServiceInterface
{
    public function __construct(RestClient $client);

    public function getClient();

    public function requestJson($params, $endpoint);

    public function requestPost($params, $endpoint);
}
