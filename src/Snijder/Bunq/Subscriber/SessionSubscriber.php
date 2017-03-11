<?php
namespace Snijder\Bunq\Subscriber;

use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;

/**
 * Class SessionSubscriber
 *
 * @package Snijder\Bunq\Subscriber
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class SessionSubscriber implements SubscriberInterface
{

    /**
     * {@inheritdoc}
     */
    public function getEvents()
    {
        return [
            'before' => ['checkSession', RequestEvents::SIGN_REQUEST]
        ];
    }

    /**
     * Checks the current session and renews it once the session is expired.
     *
     * @param BeforeEvent $event
     */
    public function checkSession(BeforeEvent $event)
    {
    }

}