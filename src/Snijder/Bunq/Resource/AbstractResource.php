<?php
namespace Snijder\Bunq\Resource;

use Snijder\Bunq\BunqClient;

/**
 * Class AbstractResource
 *
 * @package Snijder\Bunq\Service
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
abstract class AbstractResource
{
    /**
     * @var BunqClient
     */
    protected $BunqClient;

    /**
     * @var int
     */
    protected $userIdentifier;

    /**
     * AbstractResource constructor.
     *
     * @param BunqClient $BunqClient
     * @param int $userIdentifier
     */
    public function __construct(BunqClient $BunqClient, $userIdentifier = 0)
    {
        $this->BunqClient = $BunqClient;
        $this->userIdentifier = $userIdentifier;
    }

    /**
     * Returns the endpoint for the resource.
     *
     * @return string
     */
    abstract protected function getResourceEndpoint();

    /**
     * @return int
     */
    public function getUserIdentifier()
    {
        return $this->userIdentifier;
    }

    /**
     * @param int $userIdentifier
     */
    public function setUserIdentifier($userIdentifier)
    {
        $this->userIdentifier = $userIdentifier;
    }
}
