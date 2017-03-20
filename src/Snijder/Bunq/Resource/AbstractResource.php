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
    protected $client;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * AbstractResource constructor.
     *
     * @param BunqClient $bunqClient
     */
    public function __construct(BunqClient $bunqClient)
    {
        $this->client = $bunqClient;
        $this->httpClient = $bunqClient->getHttpClient();
    }

    /**
     * Returns the endpoint for the resource.
     *
     * @return string
     */
    abstract protected function getResourceEndpoint();
}
