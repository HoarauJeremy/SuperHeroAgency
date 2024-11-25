<?php

namespace App\Repository;

use App\Entity\SuperHero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SuperHero>
 */
class SuperHeroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SuperHero::class);
    }

    public function findByFilter(array $criteria): array {
        return $this->createQueryBuilder('sp')
            ->orWhere('sp.nom = :nom')
            ->setParameter(':nom', $criteria['nom'])
            ->orWhere('sp.estDisponible = :disponible')
            ->setParameter(':disponible', $criteria['disponible'])
            ->orWhere('sp.niveauEnergie = :energie')
            ->setParameter('energie', $criteria['energie'])
            ->getQuery()
            ->getResult();
        ;
    }

    public function findHeroByFilter(array $criteria): array {
        $qb = $this->createQueryBuilder('sp');

        if (!empty($criteria['nom'])) {
            $qb->andWhere('sp.nom LIKE :nom')
            ->setParameter('nom', '%' . $criteria['nom'] . '%');
        }

        if (!is_null($criteria['estDisponible'])) {
            $qb->andWhere('sp.estDisponible = :disponible')
            ->setParameter('disponible', $criteria['estDisponible']);
        }

        if (!empty($criteria['niveauEnergie'])) {
            $qb->andWhere('sp.niveauEnergie >= :energie')
            ->setParameter('energie', $criteria['niveauEnergie']);
        }

        return $qb->getQuery()->getResult();
    }
}
