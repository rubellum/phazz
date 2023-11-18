<?php

namespace CollectEngine\Operation;

use CollectEngine\Contract\ExtractorInput;
use CollectEngine\Contract\ExtractorOutput;

interface ExtractOperation
{
    public function extract(ExtractorInput $input): ExtractorOutput;
}
