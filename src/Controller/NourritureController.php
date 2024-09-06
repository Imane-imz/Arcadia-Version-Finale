<?php

namespace App\Controller;

use App\Entity\Nourriture;
use App\Form\NourritureFormType;
use App\Repository\NourritureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/nourriture', name: 'app_nourriture')]
class NourritureController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    /* #[IsGranted('ROLE_VETO')] */
    #[Route('', name: 'app_nourriture_index', methods: ['GET'])]
    public function index(NourritureRepository $repository): Response
    {
        $nourritures = $repository->findAll();

        return $this->render('nourriture/index.html.twig', [
            'controller_name' => 'NourritureController',
            'nourritures' => $nourritures,
        ]);
    }

    #[Route('/new', name: 'app_nourriture_new', methods: ['GET', 'POST'])]
    /* #[IsGranted('ROLE_VETO')] */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $nourriture = new Nourriture();
        $form = $this->createForm(NourritureFormType::class, $nourriture);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        $manager->persist($nourriture);
        $manager->flush();

        return $this->redirectToRoute('app_nourriture_show', ['id' => $nourriture->getId()]);
        }

        return $this->render('nourriture/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'app_nourriture_delete', methods: ['POST', 'DELETE'])] //La méthode POST supprime l'élément, mais pas la méthode DELETE...
    #[IsGranted('ROLE_VETO')]
    public function delete(Nourriture $nourriture, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete'.$nourriture->getId(), $request->request->get('_token'))) {
            $entityManager->remove($nourriture);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez entré la nourriture de cet animal.');
        } else {
            $this->addFlash('error', 'Échec lors du remplissage du formulaire.');
        }

        return $this->redirectToRoute('app_nourriture_index'); // Redirection vers la liste des services ou autre page
    }
}
