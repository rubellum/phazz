<?php

namespace App\Controller\OperatorPortal;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConfigurationController extends AbstractController
{
    #[Route('/config', name: 'operator_portal.config')]
    public function configAction(): Response
    {
        return $this->render('operator_portal/config.html.twig', [

        ]);
    }
}
