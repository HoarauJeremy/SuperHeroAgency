<?php

namespace App\Repository;

use App\Entity\SuperHero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<SuperHero>
 */
class SuperHeroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
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

    /**
     * Retrouve toutes les données dans le repository et permet de créer la pagination.
     * @param int $page Le numero de la page
     * @return \Knp\Component\Pager\Pagination\PaginationInterface les données pour créer la pagination
     */
    public function findAllPaginated(int $page): PaginationInterface {
        
        return $this->paginator->paginate(
            $this->createQueryBuilder('sp'),
            $page,
            10,
            [
                'distinct' => true,
                'sortFieldAllowList' => ['sp.id', 'sp.nom', 'sp.alterEgo', 'sp.estDisponible', 'sp.niveauEnergie']
            ]
        );

    }
}
