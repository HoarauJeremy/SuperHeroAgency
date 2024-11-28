<?php

namespace App\Repository;

use App\Entity\Mission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Mission>
 */
class MissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Mission::class);
    }

    /**
     * Retrouve toutes les données dans le repository et permet de créer la pagination.
     * @param int $page Le numero de la page
     * @return \Knp\Component\Pager\Pagination\PaginationInterface les données pour créer la pagination
     */
    public function findAllPaginated(int $page): PaginationInterface {
        return $this->paginator->paginate(
            $this->createQueryBuilder('m'),
            $page,
            10,
            [
                'distinct' => true,
                'sortFieldAllowList' => ['m.id', 'm.titre', 'm.statut', 'm.debut', 'm.fin', 'm.localisation', 'm.niveauDanger']
            ]
        );
    }

    public function getTauxReussiteEquipe(int $idEquipe): array
    {
        $qb = $this->createQueryBuilder('m')
            ->select('
                COUNT(m.id) AS totalMissions, 
                SUM(CASE WHEN m.statut = :statut THEN 1 ELSE 0 END) AS missionsReussies')
            ->where('m.equipeEnCharge = :idEquipe')
            ->setParameter('idEquipe', $idEquipe)
            ->setParameter('statut', 'TERMINER');

            // dd($qb->getQuery()->getResult());

        return $qb->getQuery()->getResult();
    }


}
