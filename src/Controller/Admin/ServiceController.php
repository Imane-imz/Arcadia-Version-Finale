<?php

namespace App\Controller\Admin;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/service')]
class ServiceController extends AbstractController
{

    #[IsGranted('IS_AUTHENTICATED')]
    #[Route('', name: 'app_admin_service_index', methods: ['GET'])]
    public function index(ServiceRepository $repository): Response
    {
        $services = $repository->findAll();

        return $this->render('admin_service/index.html.twig', [
            'controller_name' => 'ServiceController',
            'services' => $services,
        ]);
    }

    #[Route('/new', name: 'app_admin_service_new', methods: ['GET', 'POST'])]
    #[Route('/{id}/edit', name: 'app_admin_service_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function new(?Service $service, Request $request, EntityManagerInterface $manager): Response
    {
        if (null == $service) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN', 'ROLE_EMPLOYEE');
        }

        $service ??= new Service();
        $form = $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($service);
            $manager->flush();

            return $this->redirectToRoute('app_admin_service_show', ['id' => $service->getId()]);
        }

        return $this->render('admin_service/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_service_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Service $service): Response
    {
        return $this->render('admin_service/show.html.twig', [
            'service' => $service,
        ]);
    }
}