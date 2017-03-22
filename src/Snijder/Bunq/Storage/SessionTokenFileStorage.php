<?php
namespace Snijder\Bunq\Storage;

use Snijder\Bunq\Exception\TokenNotFoundException;
use Snijder\Bunq\Model\Token;
use Snijder\Bunq\Model\Token\SessionToken;

/**
 * Class TokenFileStorage
 *
 * @package Snijder\Bunq\Storage
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class SessionTokenFileStorage implements TokenStorageInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * TokenFileStorage constructor.
     *
     * @param $path
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
        $rawToken = file_get_contents($this->path . "/session-token.json");

        if (!$rawToken) {
            throw new TokenNotFoundException($this->path);
        }

        return SessionToken::fromArray(json_decode($rawToken, true));
    }

    /**
     * {@inheritdoc}
     */
    public function save(Token\TokenInterface $token)
    {
        file_put_contents($this->path . "/session-token.json", json_encode($token));
    }
}
