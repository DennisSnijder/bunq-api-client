<?php
namespace Snijder\Bunq;

use Ramsey\Uuid\Uuid;
use Snijder\Bunq\Factory\HttpClientFactory;

/**
 * Class Client
 *
 * @package Snijder\Bunq
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class Client
{
    /**
     * @var array
     */
    private $config;

    /**
     * The application description, in the Bunq documentation this is called the "Device description"
     *
     * @var string
     */
    private $applicationDescription = "";

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * Client constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge(
            [
                'api_key' => null,
                'api_version' => 1,
                'api_url' => 'https://sandbox.public.api.bunq.com'
            ],
            $config
        );

        $this->httpClient = HttpClientFactory::create($this->config['api_url']);
    }

    /**
     * @return string
     */
    private function getAPIVersionPrefix()
    {
        return "/v" . $this->config['api_version'];
    }

    /**
     * @return string
     */
    public function getApplicationDescription()
    {
        return $this->applicationDescription;
    }

    /**
     * @param string $applicationDescription
     */
    public function setApplicationDescription($applicationDescription)
    {
        $this->applicationDescription = $applicationDescription;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}