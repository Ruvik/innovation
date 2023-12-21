<?php

declare(strict_types=1);

namespace App\Bonus\Repository;

use App\Bonus\Entity\Bonus;
use App\Bonus\Entity\Id;
use App\Bonus\Entity\Type;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

class PostgresBonusRepository extends ServiceEntityRepository implements BonusRepositoryInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bonus::class);
    }

    public function add(Bonus $entity): void
    {
        $this->_em->persist($entity);
    }

    /**
     * @param Type $type
     * @return Id[]
     */
    public function getIdsByType(Type $type): array
    {
        $values = $this->createQueryBuilder('t')
            ->select('t.id')
            ->where('t INSTANCE OF ' . $type->getClass())
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_SCALAR);

        $ids = array_map(function ($value) {
            return new \App\Bonus\Entity\Id($value['id']);
        }, $values);

        return $ids;
    }

    public function getByIds(Id ...$ids): array
    {
        $qb = $this->createQueryBuilder('t');
        $qb->where($qb->expr()->in('t.id', ':ids'));
        $qb->setParameter('ids', $ids);
        return $qb->getQuery()->getResult();
    }
}
