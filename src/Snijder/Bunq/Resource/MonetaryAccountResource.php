<?php
namespace Snijder\Bunq\Resource;

use Snijder\Bunq\BunqClient;

/**
 * Class MonetaryAccountResource
 *
 * @package Snijder\Bunq\Resource
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class MonetaryAccountResource implements ResourceInterface
{
    /**
     * @var BunqClient
     */
    private $BunqClient;

    /**
     * @var int
     */
    private $userIdentifier;

    /**
     * MonetaryAccountResource constructor.
     *
     * @param BunqClient $BunqClient
     * @param int $userIdentifier
     */
    public function __construct(BunqClient $BunqClient, int $userIdentifier)
    {
        $this->BunqClient = $BunqClient;
        $this->userIdentifier = $userIdentifier;
    }

    /**
     * Lists all the Monetary accounts for the current user.
     *
     * @return array
     */
    public function listMonetaryAccounts()
    {
        return $this->BunqClient->getHttpService()->get(
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
        return $this->BunqClient->getHttpService()->get(
            $this->getResourceEndpoint() . "/" . $id
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceEndpoint(): string
    {
        return "/v1/user/" . $this->userIdentifier . "/monetary-account";
    }
}
