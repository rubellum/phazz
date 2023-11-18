<?php

namespace LoggingService\Contract;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

readonly class LoggingService implements LoggerInterface
{
    use LoggerTrait;

    public function __construct(
        private LoggerInterface $logger,
    )
    {

    }

    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }
}
