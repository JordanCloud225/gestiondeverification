<?php

namespace App\Repository;

use App\Entity\Client;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    //    /**
    //     * @return Client[] Returns an array of Client objects
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

    //    public function findOneBySomeField($value): ?Client
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

     
    public function getClientsThisMonth(): int
    {
        $currentMonth = (new DateTime())->format('m'); // Mois actuel
        $currentYear = (new DateTime())->format('Y');  // Année actuelle

        $qb = $this->createQueryBuilder('c')
                   ->select('COUNT(c.id) AS nbr_clients')
                   ->where('MONTH(c.createdAt) = :currentMonth')
                   ->setParameter('currentMonth', $currentMonth);

        return (int) $qb->getQuery()
                       ->getSingleScalarResult(); // Retourne un seul résultat
    }
    
    public function findbydeletedAt($ide): ?QueryBuilder
    {
        return $this->createQueryBuilder('c')
        ->andWhere('c.deletedAt IS NULL')
        ->andWhere('c.identreprise = :ide')
        ->setParameter('ide', $ide)
        ->orderBy('c.nom', 'ASC')
        ;
    }
}
