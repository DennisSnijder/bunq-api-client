<?php
namespace Snijder\Bunq;

use Snijder\Bunq\Factory\HttpClientFactory;
use Snijder\Bunq\Model\KeyPair;
use Snijder\Bunq\Service\HttpService;
use Snijder\Bunq\Service\InstallationService;
use Snijder\Bunq\Service\TokenService;
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
     * @var InstallationService
     */
    private $installationService;

    /**
     * @var TokenService
     */
    private $tokenService;

    /**
     * @var HttpService
     */
    private $httpService;

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
     * @return TokenService
     */
    public function getTokenService(): TokenService
    {
        if($this->tokenService == null) {
            $this->tokenService = new TokenService(
                $this->getInstallationService(),
                $this->getInstallationTokenStorage(),
                $this->getSessionTokenStorage()
            );
        }

        return $this->tokenService;
    }

    /**
     * @return InstallationService
     */
    public function getInstallationService(): InstallationService
    {
        if ($this->installationService == null) {
            $this->installationService = new InstallationService(
                HttpClientFactory::createInstallationClient(
                    $this->getApiUrl(),
                    $this->keyPair
                ),
                $this->keyPair,
                $this->getPermittedIps()
            );
        }

        return $this->installationService;
    }

    /**
     * @return HttpService
     */
    public function getHttpService(): HttpService
    {
        if($this->httpService == null) {
            $this->httpService = new HttpService(
                HttpClientFactory::create(
                    $this->getApiUrl(),
                    $this->getTokenService()->getSessionToken(),
                    $this->keyPair
                )
            );
        }


        return $this->httpService;
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
     * @return string
     */
    public function getApiUrl()
    {
        return "https://sandbox.public.api.bunq.com";
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

}
