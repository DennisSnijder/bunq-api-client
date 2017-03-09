<?php
namespace Snijder\Bunq;

use Ramsey\Uuid\Uuid;
use Snijder\Bunq\Factory\HttpClientFactory;

/**
 * Class Installation, used for installing your public key towards the API.
 *
 * @package Snijder\Bunq
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class Installation
{
    /**
     * Registers your public key with the Bunq API
     *
     * @param $publicKey
     * @param int $APIVersion
     * @param string $APIUrl
     * @return mixed
     */
    public static function install($publicKey, $APIVersion = 1, $APIUrl = "https://sandbox.public.api.bunq.com")
    {
        $httpClient = HttpClientFactory::create($APIUrl);

        $response = $httpClient->post( "/v" . $APIVersion . "/installation", [
            'headers' => [
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
                'User-Agent' => 'bunq-api-client:user',
                'X-Bunq-Client-Request-Id' => Uuid::uuid1(),
                'X-Bunq-Geolocation' => '0 0 0 0 NL',
                'X-Bunq-Language' => 'en_US',
                'X-Bunq-Region' => 'en_US'
            ],
            'body' => \GuzzleHttp\json_encode([
                'client_public_key' => $publicKey
            ])
        ]);

        return \GuzzleHttp\json_decode((string) $response->getBody());
    }

}