<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'app_avis')]
    public function index(): Response
    {
        return $this->render('avis/index.html.twig', [
            'controller_name' => 'AvisController',
        ]);
    }

#[Route('/avis', name: 'app_avis', methods: ['GET', 'POST'])]
    public function new(?Avis $avis, Request $request, EntityManagerInterface $manager): Response
    {

        $avis ??= new Avis();
        $form = $this->createForm(AvisFormType::class, $avis);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($avis);
            $manager->flush();

            return $this->redirectToRoute('app_avis');
        }

        return $this->render('avis/index.html.twig', [
            'form' => $form,
        ]);
    }
}