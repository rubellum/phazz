<?php

namespace CollectEngine\DataModel;

readonly class Resources
{
    public string $exportPath;

    public function __construct(
        public array  $resourceFiles = [],
        public string $exportKey = 'export',
    )
    {
        $this->exportPath = $this->resourceFiles[$this->exportKey] ?? '';
    }

    public function import(string $key, string $filePath): self
    {
        $resourceFiles = $this->resourceFiles;
        $resourceFiles[$key] = $filePath;

        return new self(
            resourceFiles: $resourceFiles,
            exportKey: $key,
        );
    }

    /**
     * @return string|null filePath
     */
    public function export(): ?string
    {
        return $this->resourceFiles[$this->exportKey] ?? null;
    }

    /**
     * @param string $key
     * @param string $filePath
     * @return self
     */
    public function add(string $key, string $filePath): self
    {
        $resourceFiles = $this->resourceFiles;
        $resourceFiles[$key] = $filePath;

        return new Resources($resourceFiles);
    }

    /**
     * @param string $key
     * @return string|null filePath
     */
    public function get(string $key): ?string
    {
        return $this->resourceFiles[$key] ?? null;
    }
}
