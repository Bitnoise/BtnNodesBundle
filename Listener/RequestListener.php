<?php

namespace Btn\NodesBundle\Listener;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Pbmc\ControlBundle\Entity\User;

class RequestListener implements EventSubscriberInterface
{
    /**
     *
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $event->getRequest()->attributes->set('_request_type', $event->getRequestType());
    }

    /**
     *
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('onKernelRequest', 200),
        );
    }
}
