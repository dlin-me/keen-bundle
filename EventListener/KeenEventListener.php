<?php

namespace Dlin\Bundle\KeenBundle\EventListener;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;


/**
 * This event listener is set up as service and is used to intercept Symfony/System events
 */
class KeenEventListener
{


    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected  $container;


    /**
     * @inheritdoc
     * @param ContainerInterface $container
     */
    function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }



    /**
     * This listener is originally implemented to trigger/force email sending, if an email is marked as urgent
     *
     * @param PostResponseEvent $event
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {

         $this->container->get('dlin.keen_service')->fireScheduledEvents();

    }



}