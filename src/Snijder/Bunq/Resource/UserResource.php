<?php
namespace Snijder\Bunq\Resource;

use GuzzleHttp\Message\ResponseInterface;

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
        return $this->BunqClient->getApiVersionPrefix() . "/user";
    }

    /**
     * @return ResponseInterface
     */
    public function getUser()
    {
        return $this->httpClient->get(
            $this->getResourceEndpoint() . "/" . $this->getUserIdentifier()
        );
    }
}
