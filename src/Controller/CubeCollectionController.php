<?php

namespace App\Controller;

use App\Repository\CubeCollectionRepository;
use App\Entity\CubeCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class CubeCollectionController extends AbstractController
{
    
    #[Route('/cube/collection', name: 'app_cube_collection_index')]
    public function index(CubeCollectionRepository $cubeCollectionRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $cubeCollections = $cubeCollectionRepository->findAll();
        } else {
            $collection = $this->getUser()->getCubeCollection();
            $cubeCollections = $collection ? [$collection] : [];
        }
        
        return $this->render('cube_collection/index.html.twig', [
            'cube_collections' => $cubeCollections,
        ]);
    }
    

    
    #[Route('/cube/collection/{id}', name: 'app_cube_collection_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $cubeCollection = $doctrine
        ->getRepository(CubeCollection::class)
        ->find($id);
        
        if (!$cubeCollection) {
            throw $this->createNotFoundException(
                'The cube collection does not exist'
                );
        }
        
        $hasAccess = $this->isGranted('ROLE_ADMIN')
        || ($this->getUser() === $cubeCollection->getMember());
        
        if (!$hasAccess) {
            throw $this->createAccessDeniedException(
                "You cannot access another member's collection!"
                );
        }
        
        return $this->render('cube_collection/show.html.twig', [
            'cube_collection' => $cubeCollection,
        ]);
    }
}
