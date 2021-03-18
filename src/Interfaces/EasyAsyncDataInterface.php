<?php

declare(strict_types=1);

namespace EonX\EasyAsync\Interfaces;

/**
 * @deprecated since 3.0.0, will be removed in 3.1. Use Batch features instead.
 */
interface EasyAsyncDataInterface
{
    public function getFinishedAt(): ?\DateTime;

    public function getId(): ?string;

    public function getStartedAt(): ?\DateTime;

    public function getStatus(): string;

    /**
     * @return mixed
     */
    public function getTargetId();

    public function getTargetType(): string;

    public function getType(): string;

    public function setFinishedAt(\DateTime $finishedAt): void;

    public function setId(string $id): void;

    public function setStartedAt(\DateTime $startedAt): void;

    public function setStatus(string $status): void;

    /**
     * @return mixed[]
     */
    public function toArray(): array;
}
