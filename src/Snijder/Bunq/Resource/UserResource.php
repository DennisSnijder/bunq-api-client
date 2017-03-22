<?php
namespace Snijder\Bunq\Resource;

/**
 * Class UserResource
 *
 * @package Snijder\Bunq\Service
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class UserResource extends AbstractResource
{

    /**
     * {@inheritdoc}
     */
    public function getResourceEndpoint()
    {
        return $this->BunqClient->getApiVersionPrefix() . "/user";
    }


    /**
     * Lists all users within the current session.
     *
     * @return array
     */
    public function listUsers()
    {
        return $this->BunqClient->requestAPI(
            "GET",
            $this->getResourceEndpoint()
        );
    }


    /**
     * Gets a user its information.
     *
     * @param null $id, if null it will use the abstract user identifier.
     * @return array
     */
    public function getUser($id = null)
    {
        if ($id == null) {
            $id = $this->getUserIdentifier();
        }

        return $this->BunqClient->requestAPI(
            "GET",
            $this->getResourceEndpoint() . "/" . $id
        );
    }
}
