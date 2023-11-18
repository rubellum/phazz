<?php

namespace JobAccess\Contract\Job;

class JobRequestValidation
{
    public function __construct(

    )
    {

    }

    public function validate(JobRequestValidationInput $input): JobRequestValidationOutput
    {
        return new JobRequestValidationOutput(
            id: 1,
        );
    }
}
