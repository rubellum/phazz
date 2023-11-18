<?php

namespace JobAccess\DataModel;

class JobSearchConditionBuilder
{
    public ?array $ids = null;
    public ?int $id = null;
    public ?string $name = null;
    public ?string $nameLike = null;
    public ?string $url = null;
    public ?string $urlLike = null;
    public ?string $type = null;
    public ?int $limit = null;
    public ?int $offset = null;
    public ?string $orderDesc = null;
    public ?string $orderAsc = null;

    public function build(): JobSearchCondition
    {
        return new JobSearchCondition(
            $this->ids,
            $this->id,
            $this->name,
            $this->nameLike,
            $this->url,
            $this->urlLike,
            $this->type,
            $this->limit,
            $this->offset,
            $this->orderDesc,
            $this->orderAsc,
        );
    }

    public function setIds(?array $ids): JobSearchConditionBuilder
    {
        $this->ids = $ids;
        return $this;
    }

    public function setId(?int $id): JobSearchConditionBuilder
    {
        $this->id = $id;
        return $this;
    }

    public function setName(?string $name): JobSearchConditionBuilder
    {
        $this->name = $name;
        return $this;
    }

    public function setNameLike(?string $nameLike): JobSearchConditionBuilder
    {
        $this->nameLike = $nameLike;
        return $this;
    }

    public function setUrl(?string $url): JobSearchConditionBuilder
    {
        $this->url = $url;
        return $this;
    }

    public function setUrlLike(?string $urlLike): JobSearchConditionBuilder
    {
        $this->urlLike = $urlLike;
        return $this;
    }

    public function setType(?string $type): JobSearchConditionBuilder
    {
        $this->type = $type;
        return $this;
    }

    public function setLimit(?int $limit): JobSearchConditionBuilder
    {
        $this->limit = $limit;
        return $this;
    }

    public function setOffset(?int $offset): JobSearchConditionBuilder
    {
        $this->offset = $offset;
        return $this;
    }

    public function setOrderDesc(?string $orderDesc): JobSearchConditionBuilder
    {
        $this->orderDesc = $orderDesc;
        return $this;
    }

    public function setOrderAsc(?string $orderAsc): JobSearchConditionBuilder
    {
        $this->orderAsc = $orderAsc;
        return $this;
    }
}
