<?php

declare(strict_types=1);

namespace App\Client\Repository;

use App\Client\Entity\Client;
use App\Client\Entity\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class PostgresClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function add(Client $entity): void
    {
        $this->_em->persist($entity);
    }

    public function remove(Client $entity): void
    {
        $this->_em->remove($entity);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get(Id $id): Client
    {
        $entity = $this->_em->find($this->_entityName, $id);
        if ($entity === null) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Client::class, [$id->getValue()]);
        }
        return $entity;
    }
}
