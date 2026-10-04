<?php

namespace App\Controller;

use App\Entity\CubeCollection;
use App\Repository\CubeCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CubeCollectionController extends AbstractController
{
    #[Route('/cube-collection', name: 'cube_collection_list')]
    public function index(CubeCollectionRepository $cubeCollectionRepository): Response
    {
        $cubeCollections = $cubeCollectionRepository->findAll();
        
        return $this->render('cube_collection/index.html.twig', [
            'cubeCollections' => $cubeCollections,
        ]);
    }
    
    #[Route('/cube-collection/{id}', name: 'cube_collection_show', requirements: ['id' => '\d+'])]
    public function show(CubeCollection $cubeCollection): Response
    {
        return $this->render('cube_collection/show.html.twig', [
            'cubeCollection' => $cubeCollection,
        ]);
    }
}