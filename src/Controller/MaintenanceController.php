<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MaintenanceController extends AbstractController
{
    public function maintenanceRedirect(): Response
    {
        return $this->render('maintenance/maintenance.html.twig', []);
    }
}
