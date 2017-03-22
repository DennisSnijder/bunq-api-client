<?php
namespace Snijder\Bunq\Resource;

/**
 * Class MonetaryAccountResource
 *
 * @package Snijder\Bunq\Resource
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class MonetaryAccountResource extends AbstractResource
{
    /**
     * {@inheritdoc}
     */
    protected function getResourceEndpoint()
    {
        return $this->BunqClient->getApiVersionPrefix() . "/user/" . $this->userIdentifier . "/monetary-account";
    }

    /**
     * Lists all the Monetary accounts for the current user.
     *
     * @return array
     */
    public function listMonetaryAccounts()
    {
        return $this->BunqClient->requestAPI(
            "GET",
            $this->getResourceEndpoint()
        );
    }

    /**
     * Gets a Monetary Account by its identifier.
     *
     * @param $id
     * @return array
     */
    public function getMonetaryAccount($id)
    {
        return $this->BunqClient->requestAPI(
            "GET",
            $this->getResourceEndpoint() . "/" . $id
        );
    }
}
