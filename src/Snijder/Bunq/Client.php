<?php
namespace Snijder\Bunq;

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
     * registers towards the Bunq API.
     */
    public function install()
    {
        $this->httpClient->post($this->getAPIVersionPrefix() . "/installation", [
            'headers' => [
                'Cache-Control' => 'no-cache',
                'User-Agent' => 'bunq-api-client:user',
                'X-Bunq-Client-Request-Id' => $this->createUUID(),
                'X-Bunq-Geolocation' => '0 0 0 0 NL',
                'X-Bunq-Language' => 'en_US',
                'X-Bunq-Region' => 'en_US'
            ]
        ]);
    }

    /**
     * @return string
     */
    private function getAPIVersionPrefix()
    {
        return "/v" . $this->config['api_version'];
    }

    private function createUUID()
    {
        $randomInput = openssl_random_pseudo_bytes(16);
        $randomInput[6] = chr(ord($randomInput[6]) & 0x0f | 0x40);
        $randomInput[8] = chr(ord($randomInput[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($randomInput), 4));
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