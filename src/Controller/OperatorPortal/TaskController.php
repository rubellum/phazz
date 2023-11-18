<?php

namespace App\Controller\OperatorPortal;

use CrawlerManager\Contract\JobExecution;
use CrawlerManager\Contract\JobExecutionInput;
use OperatorPortal\Aggregation\TaskConfirmAggregation;
use OperatorPortal\Aggregation\TaskSearchAggregation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task/search', name: 'operator_portal.task.search')]
    public function searchAction(): Response
    {
        return $this->render('operator_portal/job/task-search.html.twig');
    }

    #[Route('/api/task/search', name: 'operator_portal.api.task.search')]
    public function apiSearchAction(
        TaskSearchAggregation $aggregation,
    ): Response
    {
        return $this->json(
            $aggregation->render([])
        );
    }

    #[Route('/task/{id}', name: 'operator_portal.task.confirm')]
    public function confirmAction(
        string $id,
    ): Response
    {
        return $this->render('operator_portal/job/task-confirm.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('/api/task/confirm', name: 'operator_portal.api.task.confirm')]
    public function apiConfirmAction(
        Request                $request,
        TaskConfirmAggregation $aggregation,
    ): Response
    {
        return $this->json(
            $aggregation->render([
                'id' => $request->query->get('id'),
            ])
        );
    }

    #[Route('/api/task/run', name: 'operator_portal.api.task.run', methods: ['POST'])]
    public function runAction(
        Request      $request,
        JobExecution $workflow,
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

        $workflow->execute(
            new JobExecutionInput(
                taskId: $data['id'],
            ),
        );

        return $this->json([
            'success' => true,
            'error' => '',
            'result' => [
                'taskId' => $data['id'],
            ],
        ]);
    }
}