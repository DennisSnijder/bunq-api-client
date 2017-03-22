<?php
namespace Snijder\Bunq\Model;

/**
 * Class KeyPair, model which holds the api key, public key and private key.
 *
 * @package Snijder\Bunq\Model
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class KeyPair
{
    /**
     * @var string
     */
    private $apiKey;
    /**
     * @var string
     */
    private $publicKey;
    /**
     * @var string
     */
    private $privateKey;

    /**
     * KeyPair constructor.
     *
     * @param string $apiKey
     * @param string $publicKey
     * @param string $privateKey
     */
    public function __construct(string $apiKey, string $publicKey, string $privateKey)
    {
        $this->apiKey = $apiKey;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }
}
