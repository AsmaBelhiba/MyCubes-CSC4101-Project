<?php

namespace App\Controller;

use App\Repository\CubeCollectionRepository;
use App\Entity\CubeCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CubeCollectionController extends AbstractController
{
    #[Route('/cube/collection', name: 'app_cube_collection_index')]
    public function index(CubeCollectionRepository $cubeCollectionRepository): Response
    {
        $cubeCollections = $cubeCollectionRepository->findAll();

        $html = '<h1>Liste des collections de cubes</h1>';

        foreach ($cubeCollections as $cubeCollection) {
            $html .= '<p>';

            $html .= '<a href="' .
                $this->generateUrl('app_cube_collection_show', ['id' => $cubeCollection->getId()]) .
                '">';

            $html .= 'Collection #' . $cubeCollection->getId();
            $html .= ' : ' . $cubeCollection->getDescription();

            $html .= '</a>';
            $html .= '</p>';
        }

        return new Response($html);
    }

    #[Route('/cube/collection/{id}', name: 'app_cube_collection_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, int $id): Response
    {
        $cubeCollection = $doctrine
            ->getRepository(CubeCollection::class)
            ->find($id);

        if (!$cubeCollection) {
            throw $this->createNotFoundException('The cube collection does not exist');
        }

        return $this->render('cube_collection/show.html.twig', [
            'cube_collection' => $cubeCollection,
        ]);
    }
}
