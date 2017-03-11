<?php
namespace Snijder\Bunq\Service;

use Snijder\Bunq\Client;

/**
 * Class AbstractResource
 *
 * @package Snijder\Bunq\Service
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
abstract class AbstractResource
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * AbstractResource constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->httpClient = $client->getHttpClient();
    }

    /**
     * Returns the endpoint for the resource.
     *
     * @return string
     */
    public abstract function getResourceEndpoint();
}