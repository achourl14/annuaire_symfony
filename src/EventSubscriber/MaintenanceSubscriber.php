<?php

namespace App\EventSubscriber;

use App\Controller\MaintenanceController;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class MaintenanceSubscriber
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private MaintenanceController $maintenanceController
    ) {}

    #[AsEventListener]
    public function onKernelRequest(RequestEvent $event): void {
        $isMaintenanceActive = $this->parameterBag->get('mode_maintenance');
        if (!$isMaintenanceActive) return;

        $event->setResponse($this->maintenanceController->maintenanceRedirect());
        $event->stopPropagation();
    }
}
