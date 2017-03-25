<?php
namespace Snijder\Bunq\Factory;

use GuzzleHttp\Client;
use Snijder\Bunq\Model\KeyPair;
use Snijder\Bunq\Model\Token\SessionToken;
use Snijder\Bunq\Subscriber\RequestSigningSubscriber;
use Snijder\Bunq\Subscriber\RequestUUIDSubscriber;

/**
 * Class HttpClientFactory
 *
 * @package Snijder\Bunq
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class HttpClientFactory
{

    /**
     * Returns the standard used headers.
     *
     * @param string $url
     * @param KeyPair $keyPair
     * @return Client
     */
    private static function createBaseClient(string $url, KeyPair $keyPair)
    {
        $httpClient = new Client([
            "base_url" => $url,
            "defaults" => [
                "headers" => [
                    'Content-Type' => 'application/json',
                    'Cache-Control' => 'no-cache',
                    'User-Agent' => 'bunq-api-client:user',
                    'X-Bunq-Geolocation' => '0 0 0 0 NL',
                    'X-Bunq-Language' => 'en_US',
                    'X-Bunq-Region' => 'en_US'
                ],
                "subscribers" => [
                    new RequestUUIDSubscriber(),
                    new RequestSigningSubscriber($keyPair->getPrivateKey()),
                ]
            ]
        ]);

        return $httpClient;
    }

    /**
     * Creates an installation client.
     *
     * @param string $url
     * @param KeyPair $keyPair
     * @return Client
     */
    public static function createInstallationClient(string $url, KeyPair $keyPair)
    {
        return self::createBaseClient($url, $keyPair);
    }

    /**
     * Creates the HttpClient
     *
     * @param string $url
     * @param SessionToken $token
     * @param KeyPair $keyPair
     * @return Client
     */
    public static function create(string $url, SessionToken $token, KeyPair $keyPair)
    {
        $httpClient = self::createBaseClient($url, $keyPair);

        $httpClient->setDefaultOption("headers", array_merge(
            $httpClient->getDefaultOption("headers"),
            [
                "X-Bunq-Client-Authentication" => $token
            ]
        ));

        return $httpClient;
    }
}
