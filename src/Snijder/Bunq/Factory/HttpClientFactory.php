<?php
namespace Snijder\Bunq\Factory;

use GuzzleHttp\Client;

/**
 * Class HttpClientFactory
 *
 * @package Snijder\Bunq
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class HttpClientFactory
{
    /**
     * Creates the HttpClient
     *
     * @param $url
     * @return Client
     */
    public static function create($url)
    {
        $httpClient = new Client([
            'base_uri' => $url
        ]);

        return $httpClient;
    }
}