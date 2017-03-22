<?php
namespace Snijder\Bunq\Exception;

use Exception;
use GuzzleHttp\Exception\ClientException;

/**
 * Class BunqException, used when calling the Bunq API.
 *
 * @package Snijder\Bunq\Exception
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class BunqException extends Exception
{
    /**
     * @var ClientException
     */
    private $exception;

    /**
     * BunqException constructor.
     *
     * @param ClientException $exception
     */
    public function __construct(ClientException $exception)
    {
        $this->exception = $exception;

        parent::__construct(
            "Path: " . $exception->getRequest()->getPath() .
            ", Message: " . (string) $exception->getResponse()->getBody(),
            $exception->getCode()
        );
    }


    public function getClientException()
    {
        return $this->exception;
    }
}
