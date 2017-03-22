<?php
namespace Snijder\Bunq;

use GuzzleHttp\Exception\ClientException;
use Snijder\Bunq\Exception\BunqException;
use Snijder\Bunq\Exception\TokenNotFoundException;
use Snijder\Bunq\Factory\HttpClientFactory;
use Snijder\Bunq\Model\KeyPair;
use Snijder\Bunq\Model\Token\InstallationToken;
use Snijder\Bunq\Model\Token\SessionToken;
use Snijder\Bunq\Model\Token\TokenInterface;
use Snijder\Bunq\Storage\InstallationTokenFileStorage;
use Snijder\Bunq\Storage\SessionTokenFileStorage;
use Snijder\Bunq\Storage\TokenStorageInterface;

/**
 * Class BunqClient
 *
 * @package Snijder\Bunq
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class BunqClient
{
    /**
     * Request HTTP header constants.
     */
    const HEADER_REQUEST_AUTHORIZATION = 'Authorization'; // Not to be signed! Used in sandbox only.
    const HEADER_REQUEST_CACHE_CONTROL = 'Cache-Control';
    const HEADER_REQUEST_CONTENT_TYPE = 'Content-Type'; // Not to be signed!
    const HEADER_REQUEST_CUSTOM_CLIENT_ENCRYPTION_HMAC = 'X-Bunq-Client-Encryption-Hmac';
    const HEADER_REQUEST_CUSTOM_CLIENT_ENCRYPTION_IV = 'X-Bunq-Client-Encryption-Iv';
    const HEADER_REQUEST_CUSTOM_CLIENT_ENCRYPTION_KEY = 'X-Bunq-Client-Encryption-Key';
    const HEADER_REQUEST_CUSTOM_ATTACHMENT_DESCRIPTION = 'X-Bunq-Attachment-Description';
    const HEADER_REQUEST_CUSTOM_AUTHENTICATION = 'X-Bunq-Client-Authentication';
    const HEADER_REQUEST_CUSTOM_GEOLOCATION = 'X-Bunq-Geolocation';
    const HEADER_REQUEST_CUSTOM_LANGUAGE = 'X-Bunq-Language';
    const HEADER_REQUEST_CUSTOM_REGION = 'X-Bunq-Region';
    const HEADER_REQUEST_CUSTOM_REQUEST_ID = 'X-Bunq-Client-Request-Id';
    const HEADER_REQUEST_CUSTOM_SIGNATURE = 'X-Bunq-Client-Signature';
    const HEADER_REQUEST_USER_AGENT = 'User-Agent';

    /**
     * Bunq header prefix constants.
     */
    const HEADER_BUNQ_PREFIX = 'X-Bunq-';
    const HEADER_BUNQ_PREFIX_LENGTH = 7;
    const HEADER_BUNQ_PREFIX_START = 0;

    /**
     * Separators
     */
    const HEADER_SEPARATOR = ': '; // Mind the space after the :
    const URL_SEPARATOR = '/';

    /**
     * @var KeyPair
     */
    private $keyPair;

    /**
     * @var TokenStorageInterface
     */
    private $sessionTokenStorage;

    /**
     * @var TokenStorageInterface
     */
    private $installationTokenStorage;

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @var InstallationClient
     */
    private $installationClient;

    /**
     * @var array
     */
    private $permittedIps = [];

    /**
     * Client constructor.
     *
     * @param KeyPair $keyPair
     */
    public function __construct(KeyPair $keyPair)
    {
        $this->keyPair = $keyPair;
    }


    /**
     * @return TokenStorageInterface
     */
    public function getSessionTokenStorage(): TokenStorageInterface
    {
        if (!$this->sessionTokenStorage instanceof TokenStorageInterface) {
            $this->sessionTokenStorage = new SessionTokenFileStorage(
                sys_get_temp_dir()
            );
        }

        return $this->sessionTokenStorage;
    }

    /**
     * @return TokenStorageInterface
     */
    public function getInstallationTokenStorage(): TokenStorageInterface
    {
        if (!$this->installationTokenStorage instanceof TokenStorageInterface) {
            $this->installationTokenStorage = new InstallationTokenFileStorage(
                sys_get_temp_dir()
            );
        }

        return $this->installationTokenStorage;
    }

    /**
     * @return TokenInterface | SessionToken
     */
    public function getSessionToken(): TokenInterface
    {
        try {
            return $this->getSessionTokenStorage()->load();
        } catch (TokenNotFoundException $exception) {
            return $this->obtainNewSessionToken();
        }
    }

    /**
     * @return TokenInterface | InstallationToken
     */
    public function getInstallationToken(): TokenInterface
    {
        try {
            return $this->getInstallationTokenStorage()->load();
        } catch (TokenNotFoundException $exception) {
            $token = $this->obtainNewInstallationToken();

            //registers the device
            $this->getInstallationClient()->registerDevice(
                $token,
                $this->getPermittedIps()
            );

            return $token;
        }
    }

    /**
     * @return SessionToken
     */
    private function obtainNewSessionToken(): SessionToken
    {
        $installationClient = $this->getInstallationClient();
        $this->getInstallationToken();

        $installation = $installationClient->createSession(
            $this->getInstallationToken()
        );

        $sessionToken = SessionToken::fromGuzzleResponse($installation);
        $this->getSessionTokenStorage()->save($sessionToken);

        return $sessionToken;
    }

    /**
     * @return TokenInterface | InstallationToken
     */
    private function obtainNewInstallationToken(): TokenInterface
    {
        $installationClient = $this->getInstallationClient();
        $installation = $installationClient->install();

        $installationToken = InstallationToken::fromGuzzleResponse($installation);
        $this->getInstallationTokenStorage()->save($installationToken);

        return $installationToken;
    }

    /**
     * @return InstallationClient
     */
    private function getInstallationClient(): InstallationClient
    {
        if ($this->installationClient == null) {
            $this->installationClient = new InstallationClient(
                $this,
                HttpClientFactory::createInstallationClient($this->getApiUrl(), $this->keyPair)
            );
        }

        return $this->installationClient;
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
    public function requestAPI($method, $url, $options = []): array
    {
        $request = $this->getHttpClient()->createRequest($method, $url, $options);

        try {
            return $this->getHttpClient()->send($request)->json();
        } catch (ClientException $e) {
            throw new BunqException($e);
        }
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->keyPair->getApiKey();
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->keyPair->getPublicKey();
    }

    /**
     * @return integer
     */
    public function getApiVersion()
    {
        return 1;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return "https://sandbox.public.api.bunq.com";
    }

    /**
     * @return string
     */
    public function getApiVersionPrefix()
    {
        return "/v" . $this->getApiVersion();
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        if ($this->httpClient == null) {
            $this->httpClient = HttpClientFactory::create(
                $this->getApiUrl(),
                $this->getSessionToken(),
                $this->keyPair
            );
        }

        return $this->httpClient;
    }

    /**
     * @return array
     */
    public function getPermittedIps(): array
    {
        if (empty($this->permittedIps) && isset($_SERVER['SERVER_ADDR'])) {
            return [
                $_SERVER['SERVER_ADDR']
            ];
        }

        return $this->permittedIps;
    }

    /**
     * @param array $permittedIps
     */
    public function setPermittedIps(array $permittedIps)
    {
        $this->permittedIps = $permittedIps;
    }

    /**
     * @param string $ip
     */
    public function addPermittedIp(string $ip)
    {
        $this->permittedIps[] = $ip;
    }

    /**
     * @param TokenStorageInterface $sessionTokenStorage
     */
    public function setSessionTokenStorage(TokenStorageInterface $sessionTokenStorage)
    {
        $this->sessionTokenStorage = $sessionTokenStorage;
    }

    /**
     * @param TokenStorageInterface $installationTokenStorage
     */
    public function setInstallationTokenStorage(TokenStorageInterface $installationTokenStorage)
    {
        $this->installationTokenStorage = $installationTokenStorage;
    }
}
