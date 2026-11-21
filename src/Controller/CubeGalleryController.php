<?php

namespace App\Controller;

use App\Entity\CubeGallery;
use App\Form\CubeGalleryType;
use App\Repository\CubeGalleryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cube;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use App\Entity\Member;

#[Route('/cube/gallery')]
final class CubeGalleryController extends AbstractController
{
    
    #[Route(name: 'app_cube_gallery_index', methods: ['GET'])]
    public function index(CubeGalleryRepository $cubeGalleryRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $cubeGalleries = $cubeGalleryRepository->findAll();
        } else {
            $cubeGalleries = $cubeGalleryRepository->findBy(['publiee' => true]);
            
            if ($this->getUser() !== null) {
                $memberGalleries = $cubeGalleryRepository->findBy([
                    'member' => $this->getUser()
                ]);
                
                foreach ($memberGalleries as $gallery) {
                    $cubeGalleries[$gallery->getId()] = $gallery;
                }
                
                $cubeGalleries = array_values(
                    array_reduce($cubeGalleries, function ($result, $gallery) {
                        $result[$gallery->getId()] = $gallery;
                        return $result;
                    }, [])
                    );
            }
        }
        
        return $this->render('cube_gallery/index.html.twig', [
            'cube_galleries' => $cubeGalleries,
        ]);
    }
    

    
    #[Route('/new/{id}', name: 'app_cube_gallery_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        Member $member
        ): Response
        {
            if ($this->getUser() !== $member) {
                throw $this->createAccessDeniedException(
                    "You cannot create a gallery for another member!"
                    );
            }
            $cubeGallery = new CubeGallery();
            $cubeGallery->setMember($member);
            
            $form = $this->createForm(CubeGalleryType::class, $cubeGallery);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($cubeGallery);
                $entityManager->flush();
                
                return $this->redirectToRoute(
                    'app_member_show',
                    ['id' => $member->getId()],
                    Response::HTTP_SEE_OTHER
                    );
            }
            
            return $this->render('cube_gallery/new.html.twig', [
                'cube_gallery' => $cubeGallery,
                'form' => $form,
            ]);
    }
    
    
    #[Route('/{id}', name: 'app_cube_gallery_show', methods: ['GET'])]
    public function show(CubeGallery $cubeGallery): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN')
        || $cubeGallery->isPubliee()
        || ($this->getUser() === $cubeGallery->getMember());
        
        if (!$hasAccess) {
            throw $this->createAccessDeniedException(
                "You cannot access this private gallery!"
                );
        }
        
        return $this->render('cube_gallery/show.html.twig', [
            'cube_gallery' => $cubeGallery,
        ]);
    }
    
    
    #[Route('/{cube_gallery_id}/cube/{cube_id}', name: 'app_cube_gallery_cube_show', methods: ['GET'])]
    public function cubeShow(
        #[MapEntity(id: 'cube_gallery_id')] CubeGallery $cubeGallery,
        #[MapEntity(id: 'cube_id')] Cube $cube
        ): Response {
            if (!$cubeGallery->getCubes()->contains($cube)) {
                throw $this->createNotFoundException(
                    'The cube does not belong to this gallery.'
                    );
            }
            
            $hasAccess = $this->isGranted('ROLE_ADMIN')
            || $cubeGallery->isPubliee()
            || ($this->getUser() === $cubeGallery->getMember());
            
            if (!$hasAccess) {
                throw $this->createAccessDeniedException(
                    "You cannot access a cube from this private gallery!"
                    );
            }
            
            return $this->render('cube_gallery/cubeshow.html.twig', [
                'cube_gallery' => $cubeGallery,
                'cube' => $cube,
            ]);
    }
    

    #[Route('/{id}/edit', name: 'app_cube_gallery_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CubeGallery $cubeGallery, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $cubeGallery->getMember()) {
            throw $this->createAccessDeniedException(
                "You cannot edit another member's gallery!"
                );
        }
        $form = $this->createForm(CubeGalleryType::class, $cubeGallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            
            return $this->redirectToRoute(
                'app_member_show',
                ['id' => $cubeGallery->getMember()->getId()],
                Response::HTTP_SEE_OTHER
                );
            
        }

        return $this->render('cube_gallery/edit.html.twig', [
            'cube_gallery' => $cubeGallery,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cube_gallery_delete', methods: ['POST'])]
    public function delete(Request $request, CubeGallery $cubeGallery, EntityManagerInterface $entityManager): Response
    {
        if ($this->getUser() !== $cubeGallery->getMember()) {
            throw $this->createAccessDeniedException(
                "You cannot delete another member's gallery!"
                );
        }
        $memberId = $cubeGallery->getMember()->getId();
        
        if ($this->isCsrfTokenValid('delete'.$cubeGallery->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cubeGallery);
            $entityManager->flush();
        }

        return $this->redirectToRoute(
            'app_member_show',
            ['id' => $memberId],
            Response::HTTP_SEE_OTHER
            );
        
    }
}
