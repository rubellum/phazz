<?php

namespace JobAccess\DataModel;

readonly class JobSearchCondition
{
    public function __construct(
        public ?array $ids = null,
        public ?int $id = null,
        public ?string $name = null,
        public ?string $nameLike = null,
        public ?string $url = null,
        public ?string $urlLike = null,
        public ?string $type = null,
        public ?int $limit = null,
        public ?int $offset = null,
        public ?string $orderDesc = null,
        public ?string $orderAsc = null,
    )
    {

    }
}
