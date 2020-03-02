<?php
declare(strict_types=1);

namespace EonX\EasyAsync\Bridge;

use EonX\EasyAsync\Interfaces\JobLogFactoryInterface;
use EonX\EasyAsync\Interfaces\JobLogInterface;
use EonX\EasyAsync\Interfaces\JobLogPersisterInterface;
use EonX\EasyAsync\Interfaces\JobLogUpdaterInterface;
use EonX\EasyAsync\Interfaces\WithProcessJobLogDataInterface;

trait WithProcessJobLogTrait
{
    /**
     * @var \EonX\EasyAsync\Interfaces\JobLogInterface
     */
    private $jobLog;

    /**
     * @var \EonX\EasyAsync\Interfaces\JobLogFactoryInterface
     */
    private $jobLogFactory;

    /**
     * @var \EonX\EasyAsync\Interfaces\JobLogPersisterInterface
     */
    private $jobLogPersister;

    /**
     * @var \EonX\EasyAsync\Interfaces\JobLogUpdaterInterface
     */
    private $jobLogUpdater;

    /**
     * Get job log.
     *
     * @return null|\EonX\EasyAsync\Interfaces\JobLogInterface
     */
    public function getJobLog(): ?JobLogInterface
    {
        return $this->jobLog;
    }

    /**
     * Set job log persister.
     *
     * @param \EonX\EasyAsync\Interfaces\JobLogPersisterInterface $jobLogPersister
     *
     * @return void
     *
     * @required
     */
    public function setJobLogPersister(JobLogPersisterInterface $jobLogPersister): void
    {
        $this->jobLogPersister = $jobLogPersister;
    }

    /**
     * Set job log updater.
     *
     * @param \EonX\EasyAsync\Interfaces\JobLogUpdaterInterface $jobLogUpdater
     *
     * @return void
     *
     * @required
     */
    public function setJobLogUpdater(JobLogUpdaterInterface $jobLogUpdater): void
    {
        $this->jobLogUpdater = $jobLogUpdater;
    }

    /**
     * Set job log factory.
     *
     * @param \EonX\EasyAsync\Interfaces\JobLogFactoryInterface $jobLogFactory
     *
     * @return void
     *
     * @required
     */
    public function setJogLogFactory(JobLogFactoryInterface $jobLogFactory): void
    {
        $this->jobLogFactory = $jobLogFactory;
    }

    /**
     * @param \EonX\EasyAsync\Interfaces\WithProcessJobLogDataInterface $withData
     * @param callable $func
     *
     * @return null|mixed
     *
     * @throws \EonX\EasyAsync\Exceptions\UnableToGenerateDateTimeException
     * @throws \EonX\EasyAsync\Exceptions\UnableToPersistJobLogException
     */
    protected function processWithJobLog(WithProcessJobLogDataInterface $withData, callable $func)
    {
        $data = $withData->getProcessJobLogData();
        $this->jobLog = $jobLog = $this->jobLogFactory->create($data->getTarget(), $data->getType(), $data->getJobId());

        try {
            $this->jobLogUpdater->inProgress($jobLog);

            $result = \call_user_func($func);

            $this->jobLogUpdater->completed($jobLog);

            return $result;
        } catch (\Throwable $throwable) {
            $this->jobLogUpdater->failed($jobLog, $throwable);
        } finally {
            $this->jobLogPersister->persist($jobLog);
        }

        return null;
    }
}
