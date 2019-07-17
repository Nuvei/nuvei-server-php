<?php

namespace SafeCharge\Api\Interfaces;

use SafeCharge\Api\RestClient;

/**
 * Interface ServiceInterface
 * @package SafeCharge\Api\Interfaces
 */
interface ServiceInterface
{
    public function __construct(RestClient $client);

    public function getClient();

    public function requestJson($params, $endpoint);

    public function requestPost($params, $endpoint);
}
