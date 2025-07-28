<?php

namespace App\Repository;

use App\Entity\Typeinstrument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Typeinstrument>
 */
class TypeinstrumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typeinstrument::class);
    }

    //    /**
    //     * @return Typeinstrument[] Returns an array of Typeinstrument objects
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

    //    public function findOneBySomeField($value): ?Typeinstrument
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findbydeletedAt($ide) : QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.deletedAt IS NULL')
            ->andWhere('t.identreprise = :ide')
            ->setParameter('ide', $ide)
            ->orderBy('t.libelle', 'ASC');
    }
}
