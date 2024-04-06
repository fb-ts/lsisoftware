<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ExportHistory;
use Doctrine\DBAL\LockMode;
use Doctrine\Persistence\ManagerRegistry;

interface ExportHistoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry);

    public function find(mixed $id, LockMode|int|null $lockMode = null, ?int $lockVersion = null): ?object;

    public function findOneBy(array $criteria, array $orderBy = null);

    public function findAll();

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null);

    /**
     * @return ExportHistory[]|null
     */
    public function findByFilters(array $filters): mixed;

    public function findUniqueLocations(): array;
}
