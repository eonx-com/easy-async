<?php

declare(strict_types=1);

namespace EonX\EasyAsync\Interfaces;

interface WithProcessJobLogDataInterface
{
    public function getProcessJobLogData(): ProcessJobLogDataInterface;

    public function setJobId(string $jobId): void;

    public function setTarget(TargetInterface $target): void;

    public function setType(string $type): void;
}
