<?php
namespace Snijder\Bunq;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\ResponseInterface;
use Snijder\Bunq\BunqClient;

/**
 * Class Installation, used for installing the API / registering keys and ip's towards the server
 *
 * @package Snijder\Bunq
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class Installation
{
    /**
     * @var BunqClient
     */
    private $BunqClient;

    /**
     * Installation constructor.
     * @param \Snijder\Bunq\BunqClient $BunqClient
     */
    public function __construct(BunqClient $BunqClient)
    {
        $this->BunqClient = $BunqClient;
    }

    /**
     * Registers your public key with the Bunq API.
     *
     * @return mixed
     */
    public function install()
    {
        return $this->BunqClient->getHttpClient()->post(
            $this->BunqClient->getApiVersionPrefix() . "/installation",
            [
                'json' => [
                    'client_public_key' => $this->BunqClient->getPublicKey()
                ]
            ]
        );
    }

    /**
     * Registers a device with the Bunq API.
     *
     * @param string $description , Device description
     * @param array $ips , white-listed IPs
     * @param string $registrationToken , Registration token received from the /installation endpoint
     * @return ResponseInterface
     */
    public function registerDevice($description, array $ips, $registrationToken)
    {
        return $this->BunqClient->getHttpClient()->post(
            $this->BunqClient->getApiVersionPrefix() . "/device-server",
            [
                'headers' => [
                    'X-Bunq-Client-Authentication' => $registrationToken
                ],
                'json' => [
                    'description' => $description,
                    'secret' => $this->BunqClient->getApiKey(),
                    'permitted_ips' => $ips
                ]
            ]
        );
    }

    /**
     * Registers a session with the Bunq API.
     *
     * @param $registrationToken string, Registration token received from the /installation endpoint
     * @return mixed
     */
    public function createSession($registrationToken)
    {
        return $this->BunqClient->getHttpClient()->post(
            $this->BunqClient->getApiVersionPrefix() . "/session",
            [
                'headers' => [
                    'X-Bunq-Client-Authentication' => $registrationToken
                ],
                'json' => [
                    'secret' => $this->BunqClient->getApiKey()
                ]
            ]
        );
    }
}
