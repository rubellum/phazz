<?php

namespace OperatorPortal\Aggregation;

use CrawlerManager\Contract\JobResultRetrieving;
use CrawlerManager\Contract\JobResultRetrievingInput;
use OperatorPortal\DataModel\CompressedResources;
use Symfony\Component\Filesystem\Filesystem;

readonly class JobResultRetrievingAggregation
{
    public function __construct(
        private JobResultRetrieving $jobResultRetrieving,
    )
    {

    }

    /**
     * @param array $params
     * @return array
     */
    public function render(array $params): array
    {
        $jobResult = $this->jobResultRetrieving->retrieve(
            new JobResultRetrievingInput(
                taskId: $params['id'],
            ),
        );

        $zipPath = (new Filesystem())->tempnam(sys_get_temp_dir(), '', '.zip');
        (new CompressedResources([
            $jobResult->content->data->contentFilePath,
        ]))->writeZipArchive($zipPath);

        return [
            'zipPath' => $zipPath,
            'zipName' => $jobResult->path . '.zip',
        ];
    }
}
