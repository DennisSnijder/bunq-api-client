<?php
namespace Snijder\Bunq\Subscriber;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use Ramsey\Uuid\Uuid;
use Snijder\Bunq\BunqClient;

/**
 * Class RequestSigningSubscriber
 *
 * @package Snijder\Bunq\Subscriber
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class RequestSigningSubscriber implements SubscriberInterface
{
    /**
     * @var string
     */
    private $privateKey;

    /**
     * RequestSigningSubscriber constructor.
     *
     * @param $privateKey
     */
    public function __construct($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvents()
    {
        return [
            'before' => ['signRequest', RequestEvents::SIGN_REQUEST]
        ];
    }

    /**
     * @param BeforeEvent $event
     */
    public function signRequest(BeforeEvent $event)
    {
        $request = $event->getRequest();

        $request->addHeader(
            'X-Bunq-Client-Signature',
            $this->getSignature(
                $request->getMethod(),
                $request->getHeaders(),
                $request->getBody(),
                $request->getPath()
            )
        );
    }

    /**
     * Signs the request, thanks to the Bunq API Example for this code.
     *
     * @param $method
     * @param $headers
     * @param $body
     * @param $endpoint
     * @return string
     */
    private function getSignature($method, $headers, $body, $endpoint)
    {
        // Headers should be in alphabetic order when signing.
        ksort($headers);

        $toSign = $method. ' ' . $endpoint;

        foreach ($headers as $key => $value) {
            if ($key === "User-Agent"
                || $key === "Cache-Control"
                || substr($key, 0, 7) === 'X-Bunq-') {
                $toSign .= PHP_EOL . $key . ": " . $value[0];
            }
        }

        $toSign .= PHP_EOL . PHP_EOL;

        if (!is_null($body)) {
            $toSign .= $body;
        }

        openssl_sign($toSign, $signature, $this->privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }
}
