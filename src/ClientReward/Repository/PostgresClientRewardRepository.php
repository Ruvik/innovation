<?php

declare(strict_types=1);

namespace App\ClientReward\Repository;

use App\ClientReward\Entity\ClientReward;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostgresClientRewardRepository extends ServiceEntityRepository implements ClientRewardRepositoryInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClientReward::class);
    }

    public function add(ClientReward $entity): void
    {
        $this->_em->persist($entity);
    }

    /**
     * @param \App\Client\Entity\Id $clientId
     * @param int $limit
     * @param int $offset
     * @return ClientReward[]
     */
    public function getRewardsByClientId(\App\Client\Entity\Id $clientId, int $limit = 1000, int $offset = 0): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.clientId = :clientId')
            ->setParameter('clientId', $clientId)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
    public function getCountRewardsByClientId(\App\Client\Entity\Id $clientId): int
    {
        return (int)$this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->andWhere('t.clientId = :clientId')
            ->setParameter('clientId', $clientId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
