<?php

namespace JobAccess\DataModel;

readonly class DownloadLimit
{
    public function __construct(
        public UrlHost             $urlHost,
        public ?\DateTimeImmutable $suspendedUntil,
    )
    {

    }

    public function extendSuspended(): self
    {
        return new self(
            urlHost: $this->urlHost,
            suspendedUntil: $this->nextSuspendedUntil(),
        );
    }

    /**
     * Note: Implement a one-hour download limit per host for URLs
     *
     * @return \DateTimeImmutable
     */
    public function nextSuspendedUntil(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now + 1 min');
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return !$this->isUnavailable();
    }

    /**
     * @return bool
     */
    public function isUnavailable(): bool
    {
        if ($this->isSuspended()) {
            return true;
        }

        return false;
    }

    /**
     * @param \DateTimeImmutable|null $now
     * @return bool
     */
    public function isSuspended(?\DateTimeImmutable $now = null): bool
    {
        if ($now === null) {
            $now = new \DateTimeImmutable();
        }
        if ($this->suspendedUntil === null) {
            return false;
        }
        if ($this->suspendedUntil < $now) {
            return false;
        }
        return true;
    }
}
