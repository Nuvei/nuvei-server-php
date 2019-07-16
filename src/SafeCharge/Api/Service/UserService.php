<?php


namespace SafeCharge\Api\Service;


use SafeCharge\Api\RestClient;
use SafeCharge\Api\Exception\ConfigurationException;

/**
 * Class UserService
 * @package SafeCharge\Api\Service
 */
class UserService extends BaseService
{

    /**
     * @var UsersManagement
     */
    private $userManagementService;

    /**
     * UserService constructor.
     *
     * @param RestClient $client
     *
     * @throws ConfigurationException
     */
    public function __construct(RestClient $client)
    {
        parent::__construct($client);

        $this->userManagementService = new UsersManagement($client);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function createUser(array $params)
    {
        return $this->userManagementService->createUser($params);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function updateUser(array $params)
    {
        return $this->userManagementService->updateUser($params);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws \SafeCharge\Api\Exception\ConnectionException
     * @throws \SafeCharge\Api\Exception\ResponseException
     * @throws \SafeCharge\Api\Exception\ValidationException
     */
    public function getUserDetails(array $params)
    {
        return $this->userManagementService->getUserDetails($params);
    }
}