<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Intervention;
use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Presence;
use App\Entity\Technicien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Presence>
 */
class PresenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Presence::class);
    }

//    /**
//     * @return Presence[] Returns an array of Presence objects
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

//    public function findOneBySomeField($value): ?Presence
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
   public function findPresenceListe(): ?array
   {
       return $this->createQueryBuilder('p')
           ->innerJoin(Intervention::class, 'i', 'WITH', 'p.intervention = i.id') 
           ->innerJoin(Technicien::class, 't', 'WITH', 'p.technicien = t.id') 
           ->select('
                p.id AS id,
                i.numintervention AS numero, 
                i.demandetravaux AS demandeTravaux, 
                i.dateintervention AS dateIntervention, 
                i.site AS site, 
                i.interlocuteur AS interlocuteur, 
                t.contact AS contactTechnicien,
                t.nometprenom AS technicienNom
           ')
           ->andWhere('i.deletedAt IS NULL')
           ->getQuery()
           ->getResult();
   }
}
