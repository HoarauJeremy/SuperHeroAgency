<?php

namespace App\Controller;

use App\Entity\SuperPouvoir;
use App\Form\SuperPouvoirType;
use App\Repository\SuperPouvoirRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/superpouvoir', name: 'app_super_pouvoir_')]
final class SuperPouvoirController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(SuperPouvoirRepository $superPouvoirRepository): Response
    {
        return $this->render('super_pouvoir/index.html.twig', [
            'super_pouvoirs' => $superPouvoirRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $superPouvoir = new SuperPouvoir();
        $form = $this->createForm(SuperPouvoirType::class, $superPouvoir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($superPouvoir);
            $entityManager->flush();

            return $this->redirectToRoute('app_super_pouvoir_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('super_pouvoir/new.html.twig', [
            'super_pouvoir' => $superPouvoir,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(SuperPouvoir $superPouvoir): Response
    {
        return $this->render('super_pouvoir/show.html.twig', [
            'super_pouvoir' => $superPouvoir,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SuperPouvoir $superPouvoir, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuperPouvoirType::class, $superPouvoir);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_super_pouvoir_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('super_pouvoir/edit.html.twig', [
            'super_pouvoir' => $superPouvoir,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, SuperPouvoir $superPouvoir, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$superPouvoir->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($superPouvoir);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_super_pouvoir_index', [], Response::HTTP_SEE_OTHER);
    }
}
