<?php

namespace DZunke\FeatureFlagsBundle\EventListener;

use DZunke\FeatureFlagsBundle\Context;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ContextCreator
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->context->set('client_ip', $event->getRequest()->getClientIp());
        $this->context->set('hostname', $event->getRequest()->getHost());
    }

}
