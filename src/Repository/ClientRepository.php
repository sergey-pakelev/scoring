<?php

namespace App\Repository;

use App\Entity\Client;
use App\Exception\ClientNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function save(Client $client, bool $flush = false): void
    {
        $this->getEntityManager()->persist($client);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function saveAndFlush(Client $client): void
    {
        $this->save($client, true);
    }

    public function findById(int $id): Client
    {
        $client = $this->find($id);

        if (!$client) {
            throw new ClientNotFoundException();
        }

        return $client;
    }

    public function getAllClientsGenerator(): \Generator
    {
        $query = $this->createQueryBuilder('c')->getQuery();

        foreach ($query->toIterable() as $client) {
            yield $client;
        }
    }

    /**
     * @param int $offset
     * @param int $limit
     * @return Client[]
     */
    public function findPage(int $offset, int $limit): array
    {
        return $this->createQueryBuilder('c')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }
}
