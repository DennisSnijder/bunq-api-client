<?php
namespace Snijder\Bunq;

/**
 * Class Client
 *
 * @package Snijder\Bunq
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class Client
{
    /**
     * @var array
     */
    private $config;

    /**
     * The application descript, in the Bunq documentation this is called the "Device description"
     *
     * @var string
     */
    private $applicationDescription = "";

    /**
     * Client constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge(
            [
                'api_key' => null,
                'api_url' => 'https://sandbox.public.api.bunq.com/v1',
                'headers' => [
                ]
            ],
            $config
        );
    }

    /**
     * Authenticates towards the Bunq API.
     */
    public function authenticate()
    {
    }

    /**
     * Returns the application description.
     *
     * @return string
     */
    public function setApplicationDescription()
    {
        return $this->applicationDescription;
    }

}