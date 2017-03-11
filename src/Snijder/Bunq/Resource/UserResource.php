<?php
namespace Snijder\Bunq\Service;

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
        return "/user";
    }


    public function getUsers()
    {
       // return $this->httpClient->get();
    }

}