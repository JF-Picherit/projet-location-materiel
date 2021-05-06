<?php

namespace App\Repository;

use App\Entity\Thing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Thing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Thing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Thing[]    findAll()
 * @method Thing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Thing::class);
    }

    public function findByCategory($listCategories)
    {

        $thingFilter = $this->createQueryBuilder('t')
                    ->where("t.category IN(:listCategories)")
                    ->setParameter('listCategories', array_values($listCategories))
                    ->orderBy('t.name','asc')
                    ->getQuery()
                ;
        //dd($thingFilter);
        return $thingFilter->getResult();
    }

    // /**
    //  * @return Thing[] Returns an array of Thing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Thing
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
