<?php

namespace App\Controller\OperatorPortal;

use CrawlerManager\Contract\TaskRegistration;
use CrawlerManager\Contract\TaskRegistrationInput;
use OperatorPortal\Aggregation\JobExecutionAggregation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExecutionController extends AbstractController
{
    #[Route('/job/execution/{id}', name: 'operator_portal.job.execution.form')]
    public function formAction(
        string $id,
    ): Response
    {
        return $this->render('operator_portal/job/execution.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('/api/job/execution/form', name: 'operator_portal.api.job.execution.form')]
    public function apiFormAction(
        Request                 $request,
        JobExecutionAggregation $aggregation,
    ): Response
    {
        return $this->json(
            $aggregation->render([
                'id' => $request->query->get('id'),
            ]),
        );
    }

    #[Route('/api/job/execution/save', name: 'operator_portal.api.job.execution.save')]
    public function apiSaveAction(
        Request          $request,
        TaskRegistration $workflow,
    ): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!$this->isCsrfTokenValid('form', $data['token'] ?? '')) {
            return $this->json([
                'success' => false,
                'error' => 'Invalid token',
                'result' => [],
            ]);
        }

        $result = $workflow->execute(
            new TaskRegistrationInput(
                jobId: $data['id'],
            ),
        );

        return $this->json([
            'success' => true,
            'error' => '',
            'result' => [
                'taskId' => $result->taskId,
            ],
        ]);
    }
}
