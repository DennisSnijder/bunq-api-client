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
     * Returns the endpoint for the resource.
     *
     * @return string
     */
    public function getResourceEndpoint()
    {
        return $this->client->getApiVersionPrefix() . "/user";
    }


    public function getUser($id)
    {
        return $this->httpClient->get(
            $this->getResourceEndpoint() . "/" . $id
        );
    }
}
