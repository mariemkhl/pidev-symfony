<?php

namespace App\Repository;

use App\Entity\Paiment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Paiment>
 *
 * @method Paiment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paiment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paiment[]    findAll()
 * @method Paiment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaimentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paiment::class);
    }

    public function save(Paiment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Paiment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBynumCarte($numCarte)
    {
        return $this->createQueryBuilder('paiment')
            ->where('paiment.numCarte LIKE  :numCarte')
            ->setParameter('numCarte', '%'.$numCarte. '%')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Paiment[] Returns an array of Paiment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Paiment
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
