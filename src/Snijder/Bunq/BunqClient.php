<?php
namespace Snijder\Bunq;

use Ramsey\Uuid\Uuid;
use Snijder\Bunq\Factory\HttpClientFactory;

/**
 * Class BunqClient
 *
 * @package Snijder\Bunq
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class BunqClient
{
    /**
     * Request HTTP header constants.
     */
    const HEADER_REQUEST_AUTHORIZATION = 'Authorization'; // Not to be signed! Used in sandbox only.
    const HEADER_REQUEST_CACHE_CONTROL = 'Cache-Control';
    const HEADER_REQUEST_CONTENT_TYPE = 'Content-Type'; // Not to be signed!
    const HEADER_REQUEST_CUSTOM_CLIENT_ENCRYPTION_HMAC = 'X-Bunq-Client-Encryption-Hmac';
    const HEADER_REQUEST_CUSTOM_CLIENT_ENCRYPTION_IV = 'X-Bunq-Client-Encryption-Iv';
    const HEADER_REQUEST_CUSTOM_CLIENT_ENCRYPTION_KEY = 'X-Bunq-Client-Encryption-Key';
    const HEADER_REQUEST_CUSTOM_ATTACHMENT_DESCRIPTION = 'X-Bunq-Attachment-Description';
    const HEADER_REQUEST_CUSTOM_AUTHENTICATION = 'X-Bunq-Client-Authentication';
    const HEADER_REQUEST_CUSTOM_GEOLOCATION = 'X-Bunq-Geolocation';
    const HEADER_REQUEST_CUSTOM_LANGUAGE = 'X-Bunq-Language';
    const HEADER_REQUEST_CUSTOM_REGION = 'X-Bunq-Region';
    const HEADER_REQUEST_CUSTOM_REQUEST_ID = 'X-Bunq-Client-Request-Id';
    const HEADER_REQUEST_CUSTOM_SIGNATURE = 'X-Bunq-Client-Signature';
    const HEADER_REQUEST_USER_AGENT = 'User-Agent';

    /**
     * Bunq header prefix constants.
     */
    const HEADER_BUNQ_PREFIX = 'X-Bunq-';
    const HEADER_BUNQ_PREFIX_LENGTH = 7;
    const HEADER_BUNQ_PREFIX_START = 0;

    /**
     * Separators
     */
    const HEADER_SEPARATOR = ': '; // Mind the space after the :
    const URL_SEPARATOR = '/';

    /**
     * @var array
     */
    private $config;

    /**
     * The application description, in the Bunq documentation this is called the "Device description"
     *
     * @var string
     */
    private $applicationDescription = "";

    /**
     * @var \GuzzleHttp\Client
     */
    private $httpClient;

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
                'auth_token' => null,
                'private_key' => null,
                'public_key' => null,
                'api_version' => 1,
                'api_url' => 'https://sandbox.public.api.bunq.com'
            ],
            $config
        );

        $this->httpClient = HttpClientFactory::create(
            $this->config['api_url'],
            $this->config['auth_token'],
            $this->config['private_key']
        );
    }

    /**
     * @return string
     */
    public function getApplicationDescription()
    {
        return $this->applicationDescription;
    }

    /**
     * @param string $applicationDescription
     */
    public function setApplicationDescription($applicationDescription)
    {
        $this->applicationDescription = $applicationDescription;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->config['api_key'];
    }

    /**
     * @return string
     */
    public function getAuthToken()
    {
        return $this->config['auth_token'];
    }

    /**
     * @return integer
     */
    public function getApiVersion()
    {
        return $this->config['api_version'];
    }


    /**
     * @return string
     */
    public function getApiVersionPrefix()
    {
        return "/v" . $this->getApiVersion();
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->config['public_key'];
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }
}
