<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\Intervention;
use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Presence;
use App\Entity\Technicien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Intervention>
 */
class InterventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervention::class);
    }

//    /**
//     * @return Intervention[] Returns an array of Intervention objects
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

   public function findInterventionListe(): ?array
   {
       return $this->createQueryBuilder('i')
           ->innerJoin(Presence::class, 'p', 'WITH', 'p.intervention = i.id') 
           ->innerJoin(Client::class, 'c', 'WITH', 'i.client = c.id') 
           ->innerJoin(Marque::class, 'ma', 'WITH', 'i.marque = ma.id') 
           ->innerJoin(Modele::class, 'mo', 'WITH', 'i.modele = mo.id') 
           ->innerJoin(Technicien::class, 't', 'WITH', 't.id = p.technicien') 
           ->select('
                i.id AS id,
                i.numintervention AS numero, 
                i.demandetravaux AS demandeTravaux, 
                i.dateintervention AS dateIntervention, 
                i.site AS site, 
                i.interlocuteur AS interlocuteur, 
                c.nom AS clientnom, 
                ma.libelle AS marque,
                mo.libelle AS modele
                ')
           ->andWhere('i.deletedAt IS NULL')
           ->getQuery()
           ->getResult();
   }

    public function findbydeletedAt($ide): ?QueryBuilder
    {
        return $this->createQueryBuilder('i')
        ->andWhere('i.deletedAt IS NULL')
        ->andWhere('i.identreprise = :ide')
        ->setParameter('ide', $ide)
        ->orderBy('i.dateintervention', 'ASC')
        ;
    }
}
