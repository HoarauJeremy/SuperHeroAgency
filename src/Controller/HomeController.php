<?php

namespace App\Controller;

use App\Repository\EquipeRepository;
use App\Repository\MissionRepository;
use App\Repository\SuperHeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class HomeController extends AbstractController
{
    // #[Route('/', name: 'app_home')]
    // public function index(): Response
    // {
    //     return $this->render('home/index.html.twig', []);
    // }

    #[Route('/', name:'app_dashboard')]
    public function dashboards(MissionRepository $missions, SuperHeroRepository $superHero, EquipeRepository $equipe): Response
    {
        return $this->render('home/dashboard.html.twig', [
            'missions' => $missions->findBy(['statut' => "EN COURS"]),
            'super_heros' => $superHero->findAll(),
            'equipes' => $equipe->findAll(),
        ]);
    }

    #[Route('/statistiques', name:'app_statistiques')]
    public function statistiques(MissionRepository $missionRepository, EquipeRepository $equipeRepository, ChartBuilderInterface $chartBuilder, Request $request): Response
    {
        $id = $request->query->get('equipe');

        if ($id != null) {
            $data = $missionRepository->getTauxReussiteEquipe($id);
        }

        if (!empty($data)) {
            
            $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
            
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

            $chart->setOptions([
                'responsive' => true,
                'resize' => [
                    "width" => '250',
                    "height" => '250',
                ],
                "padding" => 20
            ]);

        } else {
            $chart = null;
        }
            
        return $this->render('home/statistiques.html.twig', [
            'chart' => $chart,
            'equipes' => $equipeRepository->findAll(),
        ]);
    }
}
