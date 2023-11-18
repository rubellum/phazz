<?php

namespace CrawlerManager\Contract;

readonly class TaskRegistrationInput
{
    public function __construct(
        public int $jobId,
    )
    {

    }
}
