<?php
namespace Snijder\Bunq;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
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
        $httpClient = new Client(["base_uri" => $APIUrl]);

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
            'body' => json_encode([
                'client_public_key' => $publicKey
            ])
        ]);

        return json_decode((string) $response->getBody());
    }

    /**
     * @param $description
     * @param $APIKey
     * @param $authToken
     * @param $privateKey
     * @param array $ips
     * @param int $APIVersion
     * @param string $APIUrl
     * @return mixed
     */
    public static function registerDevice(
        $description,
        $APIKey,
        $authToken,
        $privateKey,
        $ips = [],
        $APIVersion = 1,
        $APIUrl = "https://sandbox.public.api.bunq.com"
    ) {
        $httpClient = HttpClientFactory::create($APIUrl, $privateKey);

        try {
            $response = $httpClient->post("/v" . $APIVersion . "/device-server", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Cache-Control' => 'no-cache',
                    'User-Agent' => 'bunq-api-client:user',
                    'X-Bunq-Client-Request-Id' => Uuid::uuid1(),
                    'X-Bunq-Geolocation' => '0 0 0 0 NL',
                    'X-Bunq-Language' => 'en_US',
                    'X-Bunq-Region' => 'en_US',
                    'X-Bunq-Client-Authentication' => $authToken
                ],
                'json' => [
                    'description' => $description,
                    'secret' => $APIKey,
                    'permitted_ips' => $ips
                ]
            ]);
        } catch (ClientException $exception) {
            return json_decode( (string) $exception->getResponse()->getBody());
        }

        return json_decode((string) $response->getBody());
    }

}