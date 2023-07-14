<?php

declare(strict_types=1);

namespace EonX\EasyAsync\Bridge\Laravel\Queue;

use EonX\EasyAsync\Doctrine\ManagersClearer;
use Illuminate\Queue\Events\JobProcessing;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class DoctrineManagersClearListener
{
    private LoggerInterface $logger;

    /**
     * @param null|string[] $managers
     */
    public function __construct(
        private ManagersClearer $managersClearer,
        private ?array $managers = null,
        ?LoggerInterface $logger = null,
    ) {
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @throws \EonX\EasyAsync\Doctrine\Exceptions\DoctrineConnectionNotOkException
     * @throws \EonX\EasyAsync\Doctrine\Exceptions\DoctrineManagerClosedException
     */
    public function handle(JobProcessing $event): void
    {
        $this->logger->info('Clearing doctrine managers before job', [
            'connection' => $event->connectionName,
            'job_id' => $event->job->getJobId(),
            'job_name' => $event->job->getName(),
        ]);

        $this->managersClearer->clear($this->managers);
    }
}
