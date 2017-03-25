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
     * @var array
     */
    private $responseArray;

    /**
     * BunqException constructor.
     *
     * @param ClientException $exception
     */
    public function __construct(ClientException $exception)
    {
        $this->exception = $exception;
        $this->responseArray = $this->exception->getResponse()->json();

        parent::__construct(
            "Path: " . $exception->getRequest()->getPath() .
            ", Message: " . (string) $exception->getResponse()->getBody(),
            $exception->getCode()
        );
    }

    /**
     * @return ClientException
     */
    public function getClientException(): ClientException
    {
        return $this->exception;
    }

    /**
     * @return array
     */
    public function getResponseArray(): array
    {
        return $this->responseArray;
    }
}
