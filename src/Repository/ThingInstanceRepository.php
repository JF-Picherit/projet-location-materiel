<?php

namespace App\Repository;

use App\Entity\ThingInstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ThingInstance|null find($id, $lockMode = null, $lockVersion = null)
 * @method ThingInstance|null findOneBy(array $criteria, array $orderBy = null)
 * @method ThingInstance[]    findAll()
 * @method ThingInstance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThingInstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ThingInstance::class);
    }

    public function getNoReturn()
    {
        $thingFilter = $this->createQueryBuilder('t')
                    ->where("t.returnDate < CURRENT_TIMESTAMP() ")
                    ->getQuery()
                ;
        //dd($thingFilter, $thingFilter->getResult());
        return $thingFilter->getResult();
    }

    // /**
    //  * @return ThingInstance[] Returns an array of ThingInstance objects
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
    public function findOneBySomeField($value): ?ThingInstance
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
