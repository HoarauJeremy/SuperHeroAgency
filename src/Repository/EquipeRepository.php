<?php

namespace App\Repository;

use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Equipe>
 */
class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Equipe::class);
    }

    /**
     * Retrouve toutes les données dans le repository et permet de créer la pagination.
     * @param int $page Le numero de la page
     * @return \Knp\Component\Pager\Pagination\PaginationInterface les données pour créer la pagination
     */
    public function findAllPaginated(int $page): PaginationInterface {
        return $this->paginator->paginate(
            $this->createQueryBuilder('e'),
            $page,
            10,
            [
                'distinct' => true,
                'sortFieldAllowList' => ['e.id', 'e.nom', 'e.estActif']
            ]
        );

    }
    
}
