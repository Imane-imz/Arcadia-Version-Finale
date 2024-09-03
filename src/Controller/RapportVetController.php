<?php

namespace App\Controller;

use App\Entity\RapportVeterinaire;
use App\Repository\RapportVeterinaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/rapport-vet')]
class RapportVetController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    /* #[IsGranted('ROLE_VETO')] */
    #[Route('', name: 'app_admin_rapport_index', methods: ['GET'])]
    public function index(RapportVeterinaireRepository $repository): Response
    {
        $rapportveterinaires = $repository->findAll();

        return $this->render('rapport_vet/index.html.twig', [
            'controller_name' => 'RapportVetController',
            'rapportveterinaires' => $rapportveterinaires,
        ]);
    }

    #[Route('/rapport-vet/new', name: 'app_admin_rapport_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_VETO')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $rapportveterinaire = new RapportVeterinaire();
        $form = $this->createForm(RapportType::class, $rapportveterinaire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        $manager->persist($rapportveterinaire);
        $manager->flush();

        return $this->redirectToRoute('app_admin_rapport_show', ['id' => $rapportveterinaire->getId()]);
        }

        return $this->render('rapport_vet/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/rapport-vet/delete/{id}', name: 'app_admin_rapport_delete', methods: ['POST', 'DELETE'])] //La méthode POST supprime l'élément, mais pas la méthode DELETE...
    #[IsGranted('ROLE_VETO')]
    public function delete(RapportVeterinaire $rapportveterinaire, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérification du token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete'.$rapportveterinaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($rapportveterinaire);
            $entityManager->flush();

            $this->addFlash('success', 'Rapport supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression du rapport.');
        }

        return $this->redirectToRoute('app_admin_rapport_index'); // Redirection vers la liste des services ou autre page
    }
}

