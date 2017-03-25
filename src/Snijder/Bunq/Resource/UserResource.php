<?php
namespace Snijder\Bunq\Resource;

use Snijder\Bunq\BunqClient;

/**
 * Class UserResource
 *
 * @package Snijder\Bunq\Service
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class UserResource implements ResourceInterface
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
     * UserResource constructor.
     *
     * @param BunqClient $BunqClient
     * @param int $userIdentifier
     */
    public function __construct(BunqClient $BunqClient, int $userIdentifier = 0)
    {
        $this->BunqClient = $BunqClient;
        $this->userIdentifier = $userIdentifier;
    }

    /**
     * Lists all users within the current session.
     *
     * @return array
     */
    public function listUsers()
    {
        return $this->BunqClient->getHttpService()->get(
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
            $id = $this->userIdentifier;
        }

        return $this->BunqClient->getHttpService()->get(
            $this->getResourceEndpoint() . "/" . $id
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceEndpoint(): string
    {
        return "/v1/user";
    }
}
