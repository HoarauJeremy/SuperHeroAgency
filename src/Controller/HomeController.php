<?php

namespace App\Controller;

use App\Repository\EquipeRepository;
use App\Repository\MissionRepository;
use App\Repository\SuperHeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    #[Route('/dashboard', name:'app_dashboard')]
    public function dashboards(MissionRepository $missions, SuperHeroRepository $superHero, EquipeRepository $equipe): Response
    {
        return $this->render('home/dashboard.html.twig', [
            'missions' => $missions->findBy(['statut' => "EN COURS"]),
            'super_heros' => $superHero->findAll(),
            'equipes' => $equipe->findAll(),
        ]);
    }

    #[Route('/statistiques/{id}', name:'app_statistiques')]
    public function statistiques(MissionRepository $missionRepository, EquipeRepository $equipeRepository, ChartBuilderInterface $chartBuilder, int $id): Response
    {
        $equipe = $equipeRepository->find($id);

        if (!$equipe) {
            throw $this->createNotFoundException('Équipe introuvable');
        }

        $data = $missionRepository->getTauxReussiteEquipe($id);
        
        // dd($data);

        if (!$data || $data[0]['totalMissions'] == 0) {
            return $this->render('statistiques/equipe.html.twig', [
                'equipe' => $equipe,
                'message' => 'Aucune mission trouvée pour cette équipe.',
            ]);
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);

            $chart->setData([
                'labels' => ['Missions Réussies', 'Missions Non Réussies'],
                'datasets' => [
                    [
                        'label' => 'My First dataset',
                        'backgroundColor' => ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)'],
                        'borderColor' => ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
                        'data' => [
                            $data[0]['missionsReussies'],
                            ($data[0]['totalMissions'] - $data[0]['missionsReussies']),
                        ],
                    ],
                ],
            ]);

        return $this->render('home/statistiques.html.twig', [
            'chart' => $chart,
        ]);
    }
}
