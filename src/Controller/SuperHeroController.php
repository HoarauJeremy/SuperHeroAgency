<?php

namespace App\Controller;

use App\Entity\SuperHero;
use App\Form\FiltreType;
use App\Form\SuperHeroType;
use App\Repository\SuperHeroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/superheros', name: 'app_super_heros_')]
final class SuperHeroController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET', 'POST'])]
    public function index(SuperHeroRepository $superHeroRepository, Request $request): Response
    {
        $page = $request->query->getInt('page',1);
        // $superHeros = $superHeroRepository->findAllPaginated($page);

        // Récupérer les paramètres de filtre
        $disponible = $request->query->get('disponible'); // "true" ou "false"
        $niveauEnergie = $request->query->get('niveauEnergie'); // ex: "50"

        // Convertir les paramètres si nécessaires
        $disponible = $disponible !== null ? filter_var($disponible, FILTER_VALIDATE_BOOLEAN) : null;
        $niveauEnergie = $niveauEnergie !== null ? (int)$niveauEnergie : null;

        dd($disponible);

        // Récupérer les super-héros filtrés
        if ($disponible !== null || $niveauEnergie !== null) {
            $superHeros = $superHeroRepository->findByFiltre($page, $disponible, $niveauEnergie);
        } else {
            // Si aucun filtre n'est appliqué, paginer les résultats
            $superHeros = $superHeroRepository->findAllPaginated($page);
        }

        return $this->render('super_hero/index.html.twig', [
            'super_heroes' => $superHeros,
        ]);
    }

    /* #[Route('/filter', name:'filter')]
    public function filter(Request $request, SuperHeroRepository $superHeroRepository) : Response
    {
        
        return $this->render('super_hero/index.html.twig', [
            'super_heroes' => $superHeroRepository->findAll(),
        ]);
    } */

    /* #[Route('/available', name: 'available')]
    public function available(SuperHeroRepository $superHeroRepository, Request $request) : Response 
    {
        $page = $request->query->getInt('page',1);
        return $this->render('super_hero/index.html.twig', [
            'super_heroes' => $superHeroRepository->findAllPaginatedBy($page, true),
        ]);
    } */

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
    public function show(SuperHero $superHero): Response
    {
        return $this->render('super_hero/show.html.twig', [
            'super_hero' => $superHero,
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
