<?php
namespace Snijder\Bunq\Factory;

use GuzzleHttp\Client;
use Ramsey\Uuid\Uuid;
use Snijder\Bunq\Subscriber\RequestSigningSubscriber;
use Snijder\Bunq\Subscriber\SessionSubscriber;

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
     * @param $token
     * @param $privateKey
     * @return Client
     */
    public static function create($url, $token, $privateKey)
    {
        $httpClient = new Client([
            "base_url" => $url,
            "defaults" => [
                "headers" => [
                    'Content-Type' => 'application/json',
                    'Cache-Control' => 'no-cache',
                    'User-Agent' => 'bunq-api-client:user',
                    'X-Bunq-Client-Request-Id' => (string) Uuid::uuid4() . time(),
                    'X-Bunq-Geolocation' => '0 0 0 0 NL',
                    'X-Bunq-Language' => 'en_US',
                    'X-Bunq-Region' => 'en_US',
                    'X-Bunq-Client-Authentication' => $token
                ],
                "subscribers" => [
                    new RequestSigningSubscriber($privateKey)
                ]
            ]
        ]);


        return $httpClient;
    }
}
