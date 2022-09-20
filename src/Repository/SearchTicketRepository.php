<?php

namespace App\Repository;

use App\Entity\SearchTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SearchTicket>
 *
 * @method SearchTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method SearchTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method SearchTicket[]    findAll()
 * @method SearchTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchTicket::class);
    }

    public function add(SearchTicket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SearchTicket $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneBySearchTicket($id): ?SearchTicket
    {
        return $this->createQueryBuilder('t')
            ->select('t.from_where', 't.to_where', 't.departing_date', 't.adult', 't.kid', 't.infant')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return SearchTicket[] Returns an array of SearchTicket objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SearchTicket
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
