<?php

namespace App\Repository;

use App\Entity\Instrument;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Instrument>
 */
class InstrumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instrument::class);
    }

    //    /**
    //     * @return Instrument[] Returns an array of Instrument objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Instrument
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getInstrumentsThisMonth(): int
    {
        $currentMonth = (new DateTime())->format('m'); // Mois actuel
        $currentYear = (new DateTime())->format('Y');  // Année actuelle

        $qb = $this->createQueryBuilder('c')
                   ->select('COUNT(c.id) AS nbr_instruments')
                   ->where('MONTH(c.createdAt) = :currentMonth')
                   ->setParameter('currentMonth', $currentMonth);

        return (int) $qb->getQuery()
                       ->getSingleScalarResult(); // Retourne un seul résultat
    }
}
