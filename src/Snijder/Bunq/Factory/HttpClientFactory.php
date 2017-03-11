<?php
namespace Snijder\Bunq\Factory;

use GuzzleHttp\Client;
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
     * @param $privateKey
     * @return Client
     */
    public static function create($url, $privateKey)
    {
        $httpClient = new Client([
            "base_url" => $url,
            "defaults" => [
                "subscribers" => [
                    new SessionSubscriber(),
                    new RequestSigningSubscriber($privateKey)
                ]
            ]
        ]);


        return $httpClient;
    }
}