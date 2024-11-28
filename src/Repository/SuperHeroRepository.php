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

    /* public function findByFilter(array $criteria): array {
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
    } */

    /* public function findHeroByFilter(array $criteria): array {
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
    } */

    //-------

    
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

    /**
     * Retrouve toutes les données filtrés par les disponibilité et/ou le niveau d'énergie de l'entité {@link App\Entity\SuperHero SuperHero}.
     * @param int $page Le numéro de la pages selectioner
     * @param ?bool|null $disponible Definit si un super héros est disponible ou non  
     * @param mixed $niveauEnergie Definit le niveau d'énergie minimum d'un super héros 
     * @param string $operateur Definit le type de l'expression (AND ou OR)
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findAllPaginatedByFiltre(int $page, ?bool $disponible, ?int $niveauEnergie, string $operateur = "or"): PaginationInterface {
        $qb = $this->createQueryBuilder('f');

        if($disponible !== null || $niveauEnergie !== null) {
            // Choisir le type d'expression (AND ou OR)
            $expression = $operateur === 'and' ? $qb->expr()->andX() : $qb->expr()->orX();

            if ($disponible !== null) {
                $expression->add($qb->expr()->eq('f.estDisponible', ':disponible'));
                $qb->setParameter('disponible', $disponible);
            }
            
            if ($niveauEnergie !== null) {
                $expression->add($qb->expr()->gte('f.niveauEnergie', ':niveauEnergie'));
                $qb->setParameter('niveauEnergie', $niveauEnergie);
            }
            
            $qb->andWhere($expression);
        }

        return $this->paginator->paginate(
            $qb->getQuery()->getResult(),
            $page,
            10,
            [
                'distinct' => true,
                'sortFieldAllowList' => ['sp.id', 'sp.nom', 'sp.alterEgo', 'sp.estDisponible', 'sp.niveauEnergie']
            ]
        );
    }


    /* 
    
        ->select('e.nom AS equipeNom, 
                COUNT(m.id) AS totalMissions, 
                SUM(CASE WHEN m.statut = :statut THEN 1 ELSE 0 END) AS missionsReussies')
        ->join('m.equipeEnCharge', 'e')
        ->groupBy('e.id')
        ->setParameter('statut', 'TERMINER');
    
    */

    public function getStatutMissionParHeros(int $superHeroId): array
    {
        $qb = $this->createQueryBuilder('m')
        ->select('
            m.statut AS statut,
            COUNT(m.id) AS total
        ')
        ->join('m.equipeEnCharge', 'e')
        ->join('e.menbers', 'sh')
        ->where('sh.id = :superHeroId')
        ->setParameter('superHeroId', $superHeroId)
        ->groupBy('m.statut');

        dd($qb->getQuery()->getResult());

        return $qb->getQuery()->getResult();
    }

    public function getMissionsBySuperHero(int $superHeroId): array
{
    return $this->createQueryBuilder('m')
        ->join('m.equipeEnCharge', 'e') // Liaison entre Mission et Equipe
        ->join('e.menbers', 'sh')       // Liaison entre Equipe et SuperHero
        ->where('sh.id = :superHeroId') // Filtre par ID du SuperHero
        ->setParameter('superHeroId', $superHeroId)
        ->getQuery()
        ->getResult();
}
}
