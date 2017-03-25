<?php
namespace Snijder\Bunq\Subscriber;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use Ramsey\Uuid\Uuid;
use Snijder\Bunq\BunqClient;

/**
 * Class RequestUUIDSubscriber
 *
 * @package Snijder\Bunq\Subscriber
 */
class RequestUUIDSubscriber implements SubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function getEvents()
    {
        return [
          'before' => ['addUUID', RequestEvents::PREPARE_REQUEST]
        ];
    }

    /**
     * Adds a header to request which represents the unique request id.
     *
     * @param BeforeEvent $event
     */
    public function addUUID(BeforeEvent $event)
    {
        $request = $event->getRequest();
        $request->addHeader("X-Bunq-Client-Request-Id", Uuid::uuid4());
    }
}