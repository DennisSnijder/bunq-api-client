<?php
namespace Snijder\Bunq\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Snijder\Bunq\Exception\BunqException;


/**
 * Class HttpService, handles the HTTP Calling.
 *
 * @package Snijder\Bunq\Service
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class HttpService
{
    /**
     * @var Client
     */
    private $httpClient;

    /**
     * HttpService constructor.
     *
     * @param Client $httpClient
     */
    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param $url
     * @param array $options
     * @return array
     */
    public function get(string $url, array $options = []): array
    {
        return $this->requestAPI("GET", $url, $options);
    }

    /**
     * @param string $url
     * @param array $options
     * @return array
     */
    public function post(string $url, array $options): array
    {
        return $this->requestAPI("GET", $url, $options);
    }

    /**
     * Handles the API Calling.
     *
     * @param string $method
     * @param string $url
     * @param array $options
     * @return array
     * @throws BunqException
     */
    public function requestAPI(string $method, $url, array $options = []): array
    {
        $request = $this->httpClient->createRequest($method, $url, $options);

        try {
            return $this->httpClient->send($request)->json();
        } catch (ClientException $e) {
            throw new BunqException($e);
        }
    }

}