<?php

namespace DZunke\FeatureFlagsBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ContextCreator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $context = $this->container->get('dz.feature_flags.context');

        $context->set('client_ip', $event->getRequest()->getClientIp());
        $context->set('hostname', $event->getRequest()->getHost());
    }

}
