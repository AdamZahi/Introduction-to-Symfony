<?php

namespace App\Repository;

use App\Entity\Author;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    //    /**
    //     * @return Author[] Returns an array of Author objects
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

    //    public function findOneBySomeField($value): ?Author
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
    public function orderedListQB(): array{
        return $this->createQueryBuilder('a')
        ->orderBy("a.username","ASC")
        ->getQuery()
        ->getResult();
    }

    public function orderedListDQL(){
        $em = $this->getEntityManager();
        return  $em->createQuery(
            "select * from APP\Entity\Author a orderBy a.username"
        )->getResult();
    }
    public function showIntervalDQL($max,$min){
        $em = $this->getEntityManager();
        return  $em->createQuery(
            "select * from APP\Entity\Author a  where "
        )->getResult();
    }

    public function showMoreThan10($nb){
        return $this->createQueryBuilder('a')
        ->where("a.nb_book > :nb")
        ->setParameter('nb', $nb)
        ->getQuery()
        ->getResult();
    } 

    public function showInterval($max,$min){
        return $this->createQueryBuilder('a')
        ->where("a.nb_book > :min")
        ->andWhere("a.nb_book < :max")
        ->setParameter('min', $min)
        ->setParameter('max', $max)
        ->getQuery()
        ->getResult();
    }

    public function deleteAuthor0 (){
        $em= $this->getEntityManager();
        return $em->createQuery(
            'delete APP\Entity\Author a where a.nb_book = 0'
            )
            ->getResult();
    }

    public function countPerCategory(){
        $em = $this->getEntityManager();
        return $em->createQuery(
            'Select count '
        );
    }
}
