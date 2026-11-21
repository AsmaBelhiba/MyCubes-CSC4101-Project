<?php

namespace App\Controller;

use App\Entity\Cube;
use App\Entity\CubeCollection;
use App\Form\CubeType;
use App\Repository\CubeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cube')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CubeController extends AbstractController
{
    
    #[Route(name: 'app_cube_index', methods: ['GET'])]
    public function index(CubeRepository $cubeRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $cubes = $cubeRepository->findAll();
        } else {
            $member = $this->getUser();
            $cubeCollection = $member->getCubeCollection();
            
            $cubes = $cubeCollection
            ? $cubeCollection->getCubes()
            : [];
        }
        
        return $this->render('cube/index.html.twig', [
            'cubes' => $cubes,
        ]);
    }
    

    #[Route('/new/{id}', name: 'app_cube_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CubeCollection $cubeCollection): Response
    {
        if ($this->getUser() !== $cubeCollection->getMember()) {
            throw $this->createAccessDeniedException(
                "You cannot add a cube to another member's collection!"
                );
        }
        $cube = new Cube();
        $cube->setCubeCollection($cubeCollection);
        $form = $this->createForm(CubeType::class, $cube);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cube);
            $entityManager->flush();

            
            return $this->redirectToRoute(
                'app_cube_collection_show',
                ['id' => $cubeCollection->getId()],
                Response::HTTP_SEE_OTHER
                );
            
        }

        return $this->render('cube/new.html.twig', [
            'cube' => $cube,
            'form' => $form,
        ]);
    }

    
    #[Route('/{id}', name: 'app_cube_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Cube $cube): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN')
        || ($this->getUser() === $cube->getCubeCollection()->getMember());
        
        if (!$hasAccess) {
            throw $this->createAccessDeniedException(
                "You cannot access another member's cube!"
                );
        }
        
        return $this->render('cube/show.html.twig', [
            'cube' => $cube,
        ]);
    }
    

    #[Route('/{id}/edit', name: 'app_cube_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cube $cube, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $cube->getCubeCollection()->getMember()) {
            throw $this->createAccessDeniedException(
                "You cannot edit another member's cube!"
                );
        }
        $form = $this->createForm(CubeType::class, $cube);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute(
                'app_cube_collection_show',
                ['id' => $cube->getCubeCollection()->getId()],
                Response::HTTP_SEE_OTHER
                );
        }

        return $this->render('cube/edit.html.twig', [
            'cube' => $cube,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cube_delete', methods: ['POST'])]
    public function delete(Request $request, Cube $cube, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $cube->getCubeCollection()->getMember()) {
            throw $this->createAccessDeniedException(
                "You cannot delete another member's cube!"
                );
        }
        $collectionId = $cube->getCubeCollection()->getId();
        if ($this->isCsrfTokenValid('delete'.$cube->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cube);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'app_cube_collection_show',
            ['id' => $collectionId],
            Response::HTTP_SEE_OTHER
            );
    }
}
