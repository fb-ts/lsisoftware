<?php

namespace App\Tests\Unit\Repository;

use App\Entity\ExportHistory;
use App\Repository\ExportHistoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;

class ExportHistoryRepositoryTest extends TestCase
{
    private EntityManagerInterface $entityManagerMock;
    private ExportHistoryRepository $repository;

    protected function setUp(): void
    {
        $this->entityManagerMock = $this->createMock(EntityManager::class);
        $managerRegistryMock = $this->createMock(ManagerRegistry::class);
        $classMetadataMock = $this->createMock(ClassMetadata::class);

        $classMetadataMock->name = ExportHistory::class;

        $managerRegistryMock->method('getManagerForClass')->willReturn($this->entityManagerMock);
        $this->entityManagerMock->method('getClassMetadata')->willReturn($classMetadataMock);

        $this->repository = new ExportHistoryRepository($managerRegistryMock);
    }

    public function testFindByFiltersWithoutFilters(): void
    {
        $filters = [];

        $expectedQueryBuilder = $this->createMock(QueryBuilder::class)->select('e');

        $this->entityManagerMock
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($expectedQueryBuilder);

        $expectedQueryBuilder->expects($this->never())->method('andWhere');
        $expectedQueryBuilder->expects($this->never())->method('setParameter');

        $expectedQueryBuilder
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn($this->createMock(Query::class));

        $this->repository->findByFilters($filters);
    }

    public function testFindByFiltersWithAllFilters(): void
    {
        $filters = [
            'startDate' => '2024-04-01',
            'endDate' => '2024-04-05',
            'location' => 'Location 1',
        ];

        $expectedQueryBuilder = $this->createMock(QueryBuilder::class)->select('e');

        $this->entityManagerMock
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($expectedQueryBuilder);

        $expectedQueryBuilder
            ->expects($this->exactly(3))
            ->method('andWhere')
            ->with([
                ['e.createdAt >= :startDate'],
                ['e.createdAt <= :endDate'],
                [$expectedQueryBuilder->expr()->in('e.locationName', ':location')],
            ])
            ->willReturnSelf();

        $expectedQueryBuilder
            ->expects($this->exactly(3))
            ->method('setParameter')
            ->with([
                ['startDate', $filters['startDate']],
                ['endDate', $filters['endDate']],
                ['location', $filters['location']]
            ]);

        $expectedQueryBuilder
            ->expects($this->once())
            ->method('getQuery')
            ->willReturn($this->createMock(Query::class));

        $this->repository->findByFilters($filters);
    }
}
