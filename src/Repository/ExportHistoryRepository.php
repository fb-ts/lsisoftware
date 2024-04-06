<?php

namespace App\Repository;

use App\Entity\ExportHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExportHistory>
 *
 * @method ExportHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExportHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExportHistory[]    findAll()
 * @method ExportHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExportHistoryRepository extends ServiceEntityRepository implements ExportHistoryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExportHistory::class);
    }

    public function findByFilters(array $filters): mixed
    {
        $queryBuilder = $this->createQueryBuilder('e');

        $this->addStartDateFilter($queryBuilder, $filters);
        $this->addEndDateFilter($queryBuilder, $filters);
        $this->addLocationFilter($queryBuilder, $filters);

        return $queryBuilder->getQuery()->getResult();
    }

    private function addStartDateFilter(QueryBuilder $queryBuilder, array $filters): void
    {
        if (!empty($filters['startDate'])) {
            $queryBuilder->andWhere('e.createdAt >= :startDate')
                ->setParameter('startDate', $filters['startDate']);
        }
    }

    private function addEndDateFilter(QueryBuilder $queryBuilder, array $filters): void
    {
        if (!empty($filters['endDate'])) {
            $queryBuilder->andWhere('e.createdAt <= :endDate')
                ->setParameter('endDate', $filters['endDate']);
        }
    }

    private function addLocationFilter(QueryBuilder $queryBuilder, array $filters): void
    {
        if (!empty($filters['location'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->in('e.locationName', ':location'))
                ->setParameter('location', $filters['location']);
        }
    }

    public function findUniqueLocations(): array
    {
        $queryBuilder = $this->createQueryBuilder('e');
        return $queryBuilder->select('e.locationName')
            ->distinct()
            ->getQuery()
            ->getSingleColumnResult();
    }
}
