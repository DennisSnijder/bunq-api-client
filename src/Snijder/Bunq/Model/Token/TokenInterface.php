<?php
namespace Snijder\Bunq\Model\Token;

/**
 * Interface TokenInterface
 *
 * @package Snijder\Bunq\Model\Token
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
interface TokenInterface
{
    /**
     * Creates a token from an array.
     *
     * @param array $data
     * @return TokenInterface
     */
    public static function fromArray(array $data): TokenInterface;

    /**
     * returns the token object.
     *
     * @return TokenInterface
     */
    public function getToken(): TokenInterface;
}
