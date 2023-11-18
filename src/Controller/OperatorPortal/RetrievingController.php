<?php

namespace App\Controller\OperatorPortal;

use OperatorPortal\Aggregation\JobResultRetrievingAggregation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class RetrievingController extends AbstractController
{
    #[Route('/task/retrieve/{id}', name: 'operator_portal.job.retrieve')]
    public function retrieveAction(
        string                         $id,
        JobResultRetrievingAggregation $aggregation,
    ): Response
    {
        $result = $aggregation->render([
            'id' => $id,
        ]);

        $response = new BinaryFileResponse($result['zipPath']);
        $response->headers->set('Content-Type', 'application/zip');
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $result['zipName']);
        $response->deleteFileAfterSend();

        return $response;
    }
}
