<?php
namespace Snijder\Bunq\Storage;

use Snijder\Bunq\Model\Token;

/**
 * Interface StorageInterface
 *
 * @package Snijder\Bunq\Storage
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
interface TokenStorageInterface
{
    /**
     * Loads the token an returns a TokenInterface.
     *
     * @return Token\TokenInterface
     */
    public function load(): Token\TokenInterface;

    /**
     * Saves the token.
     *
     * @param Token\TokenInterface $token
     * @return mixed
     */
    public function save(Token\TokenInterface $token);
}
