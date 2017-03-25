<?php
namespace Snijder\Bunq\Service;
use Snijder\Bunq\Exception\TokenNotFoundException;
use Snijder\Bunq\Model\Token\InstallationToken;
use Snijder\Bunq\Model\Token\SessionToken;
use Snijder\Bunq\Model\Token\TokenInterface;
use Snijder\Bunq\Storage\TokenStorageInterface;

/**
 * Class TokenService
 *
 * @package Snijder\Bunq\Service
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class TokenService
{
    /**
     * @var TokenStorageInterface
     */
    private $installationTokenStorage;

    /**
     * @var TokenStorageInterface
     */
    private $sessionTokenStorage;

    /**
     * @var InstallationService
     */
    private $installationService;

    public function __construct(
        InstallationService $installationService,
        TokenStorageInterface $installationTokenStorage,
        TokenStorageInterface $sessionTokenStorage
    ) {
        $this->sessionTokenStorage = $sessionTokenStorage;
        $this->installationService = $installationService;
        $this->installationTokenStorage = $installationTokenStorage;
    }

    /**
     * @return TokenInterface | SessionToken
     */
    public function getSessionToken(): TokenInterface
    {
        try {
            return $this->sessionTokenStorage->load();
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
            return $this->installationTokenStorage->load();
        } catch (TokenNotFoundException $exception) {
            $token = $this->obtainNewInstallationToken();

            //registers the device
            $this->installationService->registerDevice(
                $token
            );

            return $token;
        }
    }

    /**
     * @return SessionToken
     */
    private function obtainNewSessionToken(): SessionToken
    {
        $sessionRequest = $this->installationService->createSession(
            $this->getInstallationToken()
        );

        $sessionToken = SessionToken::fromResponseArray($sessionRequest->json());
        $this->sessionTokenStorage->save($sessionToken);

        return $sessionToken;
    }

    /**
     * @return TokenInterface | InstallationToken
     */
    private function obtainNewInstallationToken(): TokenInterface
    {
        $installationRequest = $this->installationService->install();

        $installationToken = InstallationToken::fromResponseArray($installationRequest->json());
        $this->installationTokenStorage->save($installationToken);

        return $installationToken;
    }

}