<?php
namespace Snijder\Bunq\Resource;

use GuzzleHttp\Message\ResponseInterface;

/**
 * Class MonetaryAccountResource
 *
 * @package Snijder\Bunq\Resource
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class MonetaryAccountResource extends AbstractResource
{
    /**
     * Returns the endpoint for the resource.
     *
     * @return string
     */
    protected function getResourceEndpoint()
    {
        return $this->BunqClient->getApiVersionPrefix() . "/user/" . $this->userIdentifier . "/monetary-account";
    }

    /**
     * @return ResponseInterface
     */
    public function listMonetaryAccounts()
    {
        return $this->httpClient->get(
            $this->getResourceEndpoint()
        );
    }
}