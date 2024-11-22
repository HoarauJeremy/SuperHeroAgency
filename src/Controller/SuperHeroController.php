<?php

namespace App\Controller;

use App\Entity\SuperHero;
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
    #[Route(name: 'index', methods: ['GET'])]
    public function index(SuperHeroRepository $superHeroRepository): Response
    {
        return $this->render('super_hero/index.html.twig', [
            'super_heroes' => $superHeroRepository->findAll(),
        ]);
    }

    #[Route('/available', name: 'available')]
    public function available(SuperHeroRepository $superHeroRepository) : Response 
    {
        return $this->render('super_hero/index.html.twig', [
            'super_heroes' => $superHeroRepository->findBy(['estDisponible' => '1']),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManage, SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/uploads/Images')] string $imageDirectory
    ): Response
    {
        $superHero = new SuperHero();
        $form = $this->createForm(SuperHeroType::class, $superHero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $superHeroData = $form->getData();

            $superHeroImage = $form->get('nomImage')->getData();

            if ($superHeroImage) {
                $originalImageName = pathinfo($superHeroImage->getClientOriginalName(), PATHINFO_FILENAME);
                $safeImageName = $slugger->slug($originalImageName);
                $newImageName = $safeImageName.'-'.uniqid().'.'.$superHeroImage->guessExtension();

                try {
                    $superHeroImage->move($imageDirectory, $newImageName);
                } catch (FileException $e) {
                    $e->getMessage();
                }

                $superHero->setNomImage($newImageName);
            }
            $superHeroData->setCreatedAt(new \DateTimeImmutable());
            $entityManage->persist($superHeroData);
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
