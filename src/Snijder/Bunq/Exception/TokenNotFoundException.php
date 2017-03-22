<?php
namespace Snijder\Bunq\Exception;

use Exception;

/**
 * Class TokenNotFoundException
 *
 * @package Snijder\Bunq\Exception
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class TokenNotFoundException extends Exception
{
    public function __construct($path)
    {
        parent::__construct("Could not find token in path: " . $path, 0, null);
    }
}
