<?php
namespace Snijder\Bunq\Model\Token;

use GuzzleHttp\Message\ResponseInterface;
use JsonSerializable;

/**
 * Class InstallationToken
 *
 * @package Snijder\Bunq\Model
 * @author Dennis Snijder <Dennis@Snijder.io>
*/
class InstallationToken implements TokenInterface, JsonSerializable
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param array $response
     * @return InstallationToken
     */
    public static function fromResponseArray(array $response): self
    {
        return new self($response['Response'][1]['Token']['token']);
    }

    /**
     * {@inheritdoc}
     */
    public static function fromArray(array $data): TokenInterface
    {
        $token = new self($data['token']);
        return $token;
    }

    /**
     * InstallationToken constructor.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): TokenInterface
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            "token" => $this->token
        ];
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->token;
    }
}
