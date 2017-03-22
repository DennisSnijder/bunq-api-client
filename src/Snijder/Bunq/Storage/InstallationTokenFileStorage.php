<?php
namespace Snijder\Bunq\Storage;

use Snijder\Bunq\Exception\TokenNotFoundException;
use Snijder\Bunq\Model\Token;
use Snijder\Bunq\Model\Token\InstallationToken;

/**
 * Class InstallationTokenFileStorage
 *
 * @package Snijder\Bunq\Storage
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class InstallationTokenFileStorage implements TokenStorageInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * InstallationTokenFileStorage constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function load(): Token\TokenInterface
    {
        $rawToken = file_get_contents($this->path . "/installation-token.json");

        if (!$rawToken) {
            throw new TokenNotFoundException($this->path);
        }

        return InstallationToken::fromArray(json_decode($rawToken, true));
    }

    /**
     * {@inheritdoc}
     */
    public function save(Token\TokenInterface $token)
    {
        file_put_contents($this->path . "/installation-token.json", json_encode($token));
    }
}
