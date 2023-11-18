<?php

namespace App\Controller\OperatorPortal;

use JobAccess\Contract\Job\JobSaving;
use JobAccess\Contract\Job\JobSavingInput;
use OperatorPortal\Aggregation\JobConfirmAggregation;
use OperatorPortal\Aggregation\JobFormAggregation;
use OperatorPortal\Aggregation\JobSearchAggregation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JobController extends AbstractController
{
    #[Route('/job/register', name: 'operator_portal.job.register')]
    #[Route('/job/edit/{id}', name: 'operator_portal.job.edit')]
    public function formAction(?string $id): Response
    {
        return $this->render('operator_portal/job/job-form.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('/api/job/form', name: 'operator_portal.api.job.form')]
    public function apiFormAction(
        Request            $request,
        JobFormAggregation $aggregation,
    ): Response
    {
        return $this->json(
            $aggregation->render([
                'id' => $request->query->get('id'),
            ]),
        );
    }

    #[Route('/api/job/save', name: 'operator_portal.api.job.save')]
    public function apiSaveAction(
        Request   $request,
        JobSaving $jobSaving,
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

        // todo workflow
        $result = $jobSaving->save(
            new JobSavingInput(
                id: $data['id'] ?? null,
                name: $data['name'],
                url: $data['url'],
                type: $data['type'],
                operation: $data['operation'],
            ),
        );

        return $this->json([
            'success' => true,
            'error' => '',
            'result' => [
                'id' => $result->id,
            ],
        ]);
    }

    #[Route('/job/search', name: 'operator_portal.job.search')]
    public function searchAction(): Response
    {
        return $this->render('operator_portal/job/job-search.html.twig');
    }

    #[Route('/api/job/search', name: 'operator_portal.api.job.search')]
    public function apiSearchAction(
        JobSearchAggregation $aggregation,
    ): Response
    {
        return $this->json(
            $aggregation->render([]),
        );
    }

    #[Route('/job/{id}', name: 'operator_portal.job.confirm')]
    public function confirmAction(
        string $id,
    ): Response
    {
        return $this->render('operator_portal/job/job-confirm.html.twig', [
            'id' => $id,
        ]);
    }

    #[Route('/api/job/confirm', name: 'operator_portal.api.job.confirm')]
    public function apiConfirmAction(
        Request               $request,
        JobConfirmAggregation $aggregation,
    ): Response
    {
        return $this->json(
            $aggregation->render([
                'id' => $request->query->get('id'),
            ]),
        );
    }
}
