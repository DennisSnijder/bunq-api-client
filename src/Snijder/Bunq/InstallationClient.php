<?php
namespace Snijder\Bunq;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;
use Snijder\Bunq\Exception\BunqException;
use Snijder\Bunq\Model\Token\InstallationToken;

/**
 * Class InstallationClient, used for installing the API / registering keys and ip's towards the server
 *
 * @package Snijder\Bunq
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class InstallationClient
{
    /**
     * @var Client
     */
    private $installationClient;

    /**
     * @var BunqClient
     */
    private $BunqClient;

    /**
     * Installation constructor.
     * @param BunqClient $BunqClient
     * @param Client $installationClient
     */
    public function __construct(BunqClient $BunqClient, Client $installationClient)
    {
        $this->installationClient = $installationClient;
        $this->BunqClient = $BunqClient;
    }

    /**
     * Registers your public key with the Bunq API.
     *
     * @return mixed
     */
    public function install()
    {
        $request = $this->sendInstallationPostRequest(
            $this->BunqClient->getApiVersionPrefix() . "/installation",
            [
                'json' => [
                    'client_public_key' => $this->BunqClient->getPublicKey()
                ]
            ]
        );

        return $request;
    }

    /**
     * Registers a device with the Bunq API.
     *
     * @param InstallationToken $token
     * @param array $ips , white-listed IPs
     * @return ResponseInterface
     */
    public function registerDevice(InstallationToken $token, array $ips)
    {
        $request = $this->sendInstallationPostRequest(
            $this->BunqClient->getApiVersionPrefix() . "/device-server",
            [
                'headers' => [
                    'X-Bunq-Client-Authentication' => (string)$token
                ],
                'json' => [
                    'description' => "Bunq PHP API Client",
                    'secret' => $this->BunqClient->getApiKey(),
                    'permitted_ips' => $ips
                ]
            ]
        );

        return $request;
    }

    /**
     * Registers a session with the Bunq API.
     *
     * @param InstallationToken $token
     * @return ResponseInterface
     */
    public function createSession(InstallationToken $token)
    {
        $request = $this->sendInstallationPostRequest(
            $this->BunqClient->getApiVersionPrefix() . "/session-server",
            [
                'headers' => [
                    'X-Bunq-Client-Authentication' => (string) $token
                ],
                'json' => [
                    'secret' => $this->BunqClient->getApiKey()
                ]
            ]
        );

        return $request;
    }


    private function sendInstallationPostRequest($url, array $options = [])
    {
        try {
            return $this->installationClient->post($url, $options);
        } catch (ClientException $exception) {
            throw new BunqException($exception);
        }
    }
}
