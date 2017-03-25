<?php
namespace Snijder\Bunq\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\ResponseInterface;
use Snijder\Bunq\Exception\BunqException;
use Snijder\Bunq\Model\KeyPair;
use Snijder\Bunq\Model\Token\InstallationToken;

/**
 * Class InstallationService, used for installing the API / registering keys and ip's towards the server
 *
 * @package Snijder\Bunq
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class InstallationService
{
    /**
     * @var Client
     */
    private $installationHttpClient;
    /**
     * @var KeyPair
     */
    private $keyPair;

    /**
     * @var array
     */
    private $permittedIps;

    /**
     * InstallationService constructor.
     *
     * @param Client $installationHttpClient
     * @param KeyPair $keyPair
     * @param array $permittedIps
     */
    public function __construct(
        Client $installationHttpClient,
        KeyPair $keyPair,
        array $permittedIps
    ) {
        $this->installationHttpClient = $installationHttpClient;
        $this->keyPair = $keyPair;
        $this->permittedIps = $permittedIps;
    }

    /**
     * Registers your public key with the Bunq API.
     *
     * @return mixed
     */
    public function install()
    {
        $request = $this->sendInstallationPostRequest(
            "/v1/installation",
            [
                'json' => [
                    'client_public_key' => $this->keyPair->getPublicKey()
                ]
            ]
        );

        return $request;
    }

    /**
     * Registers a device with the Bunq API.
     *
     * @param InstallationToken $token
     * @return ResponseInterface
     */
    public function registerDevice(InstallationToken $token)
    {
        $request = $this->sendInstallationPostRequest(
            "/v1/device-server",
            [
                'headers' => [
                    'X-Bunq-Client-Authentication' => (string)$token
                ],
                'json' => [
                    'description' => "Bunq PHP API Client",
                    'secret' => $this->keyPair->getApiKey(),
                    'permitted_ips' => $this->permittedIps
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
             "/v1/session-server",
            [
                'headers' => [
                    'X-Bunq-Client-Authentication' => (string) $token
                ],
                'json' => [
                    'secret' => $this->keyPair->getApiKey()
                ]
            ]
        );

        return $request;
    }

    /**
     * Sends a post request using the installation HTTP Client
     *
     * @param $url
     * @param array $options
     * @return ResponseInterface
     * @throws BunqException
     */
    private function sendInstallationPostRequest($url, array $options = [])
    {
        try {
            return $this->installationHttpClient->post($url, $options);
        } catch (ClientException $exception) {
            throw new BunqException($exception);
        }
    }
}
