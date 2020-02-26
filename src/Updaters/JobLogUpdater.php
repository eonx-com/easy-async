<?php
declare(strict_types=1);

namespace EonX\EasyAsync\Updaters;

use EonX\EasyAsync\Interfaces\DateTimeGeneratorInterface;
use EonX\EasyAsync\Interfaces\JobLogInterface;
use EonX\EasyAsync\Interfaces\JobLogUpdaterInterface;

final class JobLogUpdater implements JobLogUpdaterInterface
{
    /**
     * @var \EonX\EasyAsync\Interfaces\DateTimeGeneratorInterface
     */
    private $datetime;

    /**
     * JobLogUpdater constructor.
     *
     * @param \EonX\EasyAsync\Interfaces\DateTimeGeneratorInterface $datetime
     */
    public function __construct(DateTimeGeneratorInterface $datetime)
    {
        $this->datetime = $datetime;
    }

    /**
     * Update given jobLog to completed.
     *
     * @param \EonX\EasyAsync\Interfaces\JobLogInterface $jobLog
     *
     * @return void
     *
     * @throws \EonX\EasyAsync\Exceptions\UnableToGenerateDateTimeException
     */
    public function completed(JobLogInterface $jobLog): void
    {
        $jobLog->setStatus(JobLogInterface::STATUS_COMPLETED);
        $jobLog->setFinishedAt($this->datetime->now());
    }

    /**
     * Update given jobLog to failed for given throwable.
     *
     * @param \EonX\EasyAsync\Interfaces\JobLogInterface $jobLog
     * @param \Throwable $throwable
     *
     * @return void
     *
     * @throws \EonX\EasyAsync\Exceptions\UnableToGenerateDateTimeException
     */
    public function failed(JobLogInterface $jobLog, \Throwable $throwable): void
    {
        $jobLog->setStatus(JobLogInterface::STATUS_FAILED);
        $jobLog->setFinishedAt($this->datetime->now());
        $jobLog->addDebugInfo('exception', [
            'class' => \get_class($throwable),
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTraceAsString()
        ]);
    }

    /**
     * Update given jobLog to in progress.
     *
     * @param \EonX\EasyAsync\Interfaces\JobLogInterface $jobLog
     *
     * @return void
     *
     * @throws \EonX\EasyAsync\Exceptions\UnableToGenerateDateTimeException
     */
    public function inProgress(JobLogInterface $jobLog): void
    {
        $jobLog->setStatus(JobLogInterface::STATUS_IN_PROGRESS);
        $jobLog->setStartedAt($this->datetime->now());
    }
}
