<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findArticleByNom($titreArticle)
    {
        return $this->createQueryBuilder('evenement')
            ->where('evenement.titreArticle LIKE  :titreArticle')
            ->setParameter('titreArticle', '%'.$titreArticle. '%')
            ->getQuery()
            ->getResult();
    }
    public function findEvenementByNom($nom)
    {
        return $this->createQueryBuilder('evenement')
            ->where('evenement.nom LIKE  :nom')
            ->setParameter('nom', '%'.$nom. '%')
            ->getQuery()
            ->getResult();
    }


//     public function searchByTitle(string $searchTerm): array
// {
//     $qb = $this->createQueryBuilder('a')
//         ->where('a.titreArticle LIKE :searchTerm')
//         ->setParameter('searchTerm', '%'.$searchTerm.'%')
//         ->getQuery();

//     return $qb->getResult();
// }

public function findByCategory($categoryArticle)
{
    
    return $this->createQueryBuilder('a')
        ->andWhere('a.categoryArticle = :categoryArticle')
        ->setParameter('categoryArticle, $categoryArticle')
        ->getQuery()
        ->getResult();
}


//     public function searchByTerm(string $searchTerm): array
// {
//     $qb = $this->createQueryBuilder('a')
//         ->where('a.titreArticle LIKE :searchTerm')
//         ->orWhere('a.contentArticle LIKE :searchTerm')
//         ->setParameter('searchTerm', '%'.$searchTerm.'%')
//         ->getQuery();

//     return $qb->getResult();
// }


//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
