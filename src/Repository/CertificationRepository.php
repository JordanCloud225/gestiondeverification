<?php

namespace App\Repository;

use App\Entity\Certification;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Certification>
 */
class CertificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Certification::class);
    }

    //    /**
    //     * @return Certification[] Returns an array of Certification objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Certification
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getCertificationThisMonth(): int
    {
        $currentMonth = (new DateTime())->format('m'); // Mois actuel
        $currentYear = (new DateTime())->format('Y');  // Année actuelle

        $qb = $this->createQueryBuilder('c')
                   ->select('COUNT(c.id) AS nbr_certifications')
                   ->where('MONTH(c.createdAt) = :currentMonth')
                   ->setParameter('currentMonth', $currentMonth);

        return (int) $qb->getQuery()
                       ->getSingleScalarResult(); // Retourne un seul résultat
    }
}
