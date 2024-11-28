<?php

namespace App\Controller;

use App\Repository\EquipeRepository;
use App\Repository\MissionRepository;
use App\Repository\SuperHeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
        return $this->render('dashboard/index.html.twig', [
            'missions' => $missions->findBy(['statut' => "EN COURS"]),
            'super_heros' => $superHero->findAll(),
            'equipes' => $equipe->findAll(),
        ]);
    }

    #[Route('/statistiques', name:'app_statistiques')]
    public function statistiques(): Response
    {
        return $this->render('home/statistiques.html.twig', []);
    }
}
