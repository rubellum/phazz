<?php

namespace OperatorPortal\DataAccess;

use OperatorPortal\DataModel\FormJsonSchema;

readonly class FormJsonSchemaLoader
{
    public function __construct(
        private string $schemaDir,
    )
    {

    }

    public function load(FormJsonSchema $jsonSchema): array
    {
        return json_decode(file_get_contents($this->schemaDir . DIRECTORY_SEPARATOR . $jsonSchema->value . '.json'), true);
    }
}
