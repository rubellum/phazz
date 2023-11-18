<?php

namespace OperatorPortal\DataModel;

enum FormJsonSchema: string
{
    case jobRegistration = 'jobRegistration';
    case jobRegistrationUI = 'jobRegistrationUI';
    case jobEdit = 'jobEdit';
    case jobEditUI = 'jobEditUI';

    case jobForm = 'jobForm';
    case jobFormUI = 'jobFormUI';

    case schemaForm = 'schemaForm';
    case schemaFormUI = 'schemaFormUI';
}
