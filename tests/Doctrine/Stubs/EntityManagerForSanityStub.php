<?php
declare(strict_types=1);

namespace EonX\EasyAsync\Tests\Doctrine\Stubs;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Mysqli\Driver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Decorator\EntityManagerDecorator;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\Mapping\Driver\StaticPHPDriver;

final class EntityManagerForSanityStub extends EntityManagerDecorator
{
    public function __construct(
        private readonly bool $isOpen,
        ?string $connectionClass = null,
    ) {
        $config = new Configuration();
        $config->setMetadataDriverImpl(new StaticPHPDriver([]));
        $config->setProxyDir(__DIR__);
        $config->setProxyNamespace('Proxies');

        $eventManager = new EventManager();

        $connectionClass ??= Connection::class;
        /** @var \Doctrine\DBAL\Connection $conn */
        $conn = new $connectionClass([], new Driver(), null, $eventManager);

        parent::__construct(
            EntityManager::create($conn, $config, $eventManager)
        );
    }

    public function isOpen(): bool
    {
        return $this->isOpen;
    }
}
