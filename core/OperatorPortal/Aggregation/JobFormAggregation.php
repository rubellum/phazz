<?php

namespace OperatorPortal\Aggregation;

use JobAccess\Contract\Job\JobConfirmation;
use JobAccess\Contract\Job\JobConfirmationInput;
use OperatorPortal\DataAccess\FormJsonSchemaLoader;
use OperatorPortal\DataModel\FormJsonSchema;

readonly class JobFormAggregation
{
    public function __construct(
        private JobConfirmation      $confirmation,
        private FormJsonSchemaLoader $formSchemaLoader,
    )
    {

    }

    public function render(array $params): array
    {
        if (isset($params['id'])) {
            $result = $this->confirmation->confirm(
                new JobConfirmationInput(
                    id: $params['id'],
                ),
            );
            $job = $result->job;
            $formData = [
                'id' => $job->id,
                'name' => $job->name,
                'url' => $job->url,
                'type' => $job->type,
                'operation' => $job->operation,
            ];
        } else {
            $formData = [
                'id' => null,
                'name' => '',
                'url' => '',
                'type' => 'single',
                'operation' => [],
            ];
        }

        $formSchema = $this->formSchemaLoader->load(FormJsonSchema::jobForm);

        return [
            'formSchema' => $formSchema,
            'uiSchema' => $this->formSchemaLoader->load(FormJsonSchema::jobFormUI),
            'formData' => $formData,
        ];
    }
}
