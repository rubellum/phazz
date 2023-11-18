<?php

namespace App\Controller\OperatorPortal;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'operator_portal.index', methods: ['GET'])]
    public function indexAction(): Response
    {
        return $this->render('operator_portal/index.html.twig');
    }
}
