<?php

namespace App\Controller;

use App\Entity\SuperHero;
use App\Form\SuperHeroType;
use App\Repository\SuperHeroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

#[Route('/superheros', name: 'app_super_heros_')]
final class SuperHeroController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET', 'POST'])]
    public function index(SuperHeroRepository $superHeroRepository, Request $request): Response
    {
        $page = $request->query->getInt('page',1);

        // Récupérer les paramètres de filtre
        $disponible = $request->query->get('disponible'); // "true" ou "false"
        $niveauEnergie = $request->query->get('niveauEnergie'); // ex: "50"
        $operateur = $request->query->get('operateur', 'or'); // "OR" ou "AND"

        // Convertir les paramètres si nécessaires
        $disponible = $disponible !== null && $disponible !== '' ? filter_var($disponible, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null;
        $niveauEnergie = $niveauEnergie !== null && $niveauEnergie !== '' ? (int)$niveauEnergie : null;

        if($disponible !== null || $niveauEnergie !== null) {
            // Récupérer les super-héros filtrés
            $superHeros = $superHeroRepository->findAllPaginatedByFiltre($page, $disponible, $niveauEnergie, $operateur);
        } else {
            // Si aucun filtre n'est appliqué, paginer les résultats
            $superHeros = $superHeroRepository->findAllPaginated($page);
        }

        return $this->render('super_hero/index.html.twig', [
            'super_heroes' => $superHeros,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManage): Response
    {
        $superHero = new SuperHero();
        $form = $this->createForm(SuperHeroType::class, $superHero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $superHeroData = $form->getData();
            // $superHeroData->setCreatedAt(new \DateTimeImmutable());
            $superHero->setCreatedAt(new \DateTimeImmutable());
            $entityManage->persist($superHero);
            $entityManage->flush();

            return $this->redirectToRoute('app_super_heros_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('super_hero/new.html.twig', [
            'super_hero' => $superHero,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(SuperHero $superHero, int $id, SuperHeroRepository $superHeroRepository, ChartBuilderInterface $chartBuilder): Response
    {

        $data = $superHeroRepository->getStatutMissionParHeros($id);
        
        if (!empty($data)) {
            $chart = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
            
            $chart->setData([
                'labels' => ['En Attente', 'En Cours', 'Terminée', 'Échouée'],
                'datasets' => [
                    [
                        // 'label' => 'My First dataset',
                        'backgroundColor' => ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(54, 235, 162, 0.2)', 'rgba(255, 0, 255, 0.2)'],
                        'borderColor' => ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)', 'rgba(54, 235, 162, 1)', 'rgba(255, 0, 255, 1)'],
                        'data' => [
                            isset($data[0]['total']) ? $data[0]['total'] : "",
                            isset($data[1]['total']) ? $data[1]['total'] : "",
                            isset($data[2]['total']) ? $data[2]['total'] : "",
                            isset($data[3]['total']) ? $data[3]['total'] : "",
                        ],
                    ],
                ],
            ]);
        } else {
            $chart = null;
        }
        
        return $this->render('super_hero/show.html.twig', [
            'super_hero' => $superHero,
            'chart' => $chart,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuperHero $superHero, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuperHeroType::class, $superHero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_super_heros_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('super_hero/edit.html.twig', [
            'super_hero' => $superHero,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, SuperHero $superHero, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$superHero->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($superHero);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_super_heros_index', [], Response::HTTP_SEE_OTHER);
    }
}
