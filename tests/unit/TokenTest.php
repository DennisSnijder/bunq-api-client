<?php
namespace Snijder\Bunq\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Snijder\Bunq\Model\Token\InstallationToken;
use Snijder\Bunq\Model\Token\SessionToken;
use Snijder\Bunq\Model\Token\TokenInterface;

/**
 * Class TokenTest, tests creation and modification of tokens.
 *
 * @package Snijder\Bunq\Tests\Unit
 */
class TokenTest extends TestCase
{

    /**
     * Tests the named constructor "fromArray" for the SessionToken object
     */
    public function testSessionTokenFromArrayCreation()
    {
        $this->assertInstanceOf(
            SessionToken::class,
            SessionToken::fromArray([
                "token" => "1"
            ])
        );
    }

    /**
     * Tests the named constructor "fromArray" for the InstallationToken object
     */
    public function testInstallationTokenFromArrayCreation()
    {
        $this->assertInstanceOf(
            InstallationToken::class,
            InstallationToken::fromArray([
                "token" => "1"
            ])
        );
    }

    /**
     * Tests if the tokens implement the TokenInterface
     */
    public function testTokenInterfaceImplementing()
    {
        $this->assertContainsOnlyInstancesOf(
            TokenInterface::class,
            [
                new SessionToken("1"),
                new InstallationToken("1")
            ]
        );
    }

}
