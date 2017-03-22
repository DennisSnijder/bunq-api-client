<?php
namespace Snijder\Bunq\Model\Token;

use GuzzleHttp\Message\ResponseInterface;
use JsonSerializable;

/**
 * Class SessionToken
 *
 * @package Snijder\Bunq\Model
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class SessionToken implements TokenInterface, JsonSerializable
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param ResponseInterface $response
     * @return SessionToken
     */
    public static function fromGuzzleResponse(ResponseInterface $response)
    {
        $JsonResponse = $response->json();
        return new self($JsonResponse['Response'][1]['Token']['token']);
    }

    /**
     * @param array $tokenData
     * @return TokenInterface
     */
    public static function fromArray(array $tokenData): TokenInterface
    {
        $token = new self($tokenData['token']);
        return $token;
    }

    /**
     * Token constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->token;
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
     * {@inheritdoc}
     */
    public function getToken(): TokenInterface
    {
        return $this;
    }
}
