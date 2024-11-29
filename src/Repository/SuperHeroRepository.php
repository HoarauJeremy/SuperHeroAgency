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

    public function getStatutMissionParHeros(int $superHeroId): array
    {
        return $this->createQueryBuilder('sh')
            ->select('m.statut, COUNT(m.id) as total')
            ->join('sh.equipes', 'e')          // Liaison entre SuperHero et Equipe
            ->join('e.missions', 'm')          // Liaison entre Equipe et Mission
            ->where('sh.id = :superHeroId')    // Filtre par ID du SuperHero
            ->groupBy('m.statut')              // Groupement par statut
            ->setParameter('superHeroId', $superHeroId)
            ->getQuery()
            ->getResult();
    }
}
