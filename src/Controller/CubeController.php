<?php

namespace App\Controller;

use App\Repository\CubeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cube;
use Doctrine\Persistence\ManagerRegistry;

final class CubeController extends AbstractController
{
    #[Route('/cube', name: 'cube_list')]
    public function index(CubeRepository $cubeRepository): Response
    {
        $cubes = $cubeRepository->findAll();
        
        $html = '<h1>Liste des cubes</h1>';
        
        foreach ($cubes as $cube) {
            $html .= '<p>';
            
            $html .= '<a href="' .
                $this->generateUrl('cube_show', ['id' => $cube->getId()]) .
                '">';
                
                $html .= 'Cube #' . $cube->getId();
                $html .= ' : ' . $cube->getDescription();
                
                $html .= '</a>';
                $html .= '</p>';
        }
        
        return new Response($html);
    }
    
    #[Route('/cube/{id}', name: 'cube_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id): Response
    {
        $cubeRepo = $doctrine->getRepository(Cube::class);
        $cube = $cubeRepo->find($id);
        
        if (!$cube) {
            throw $this->createNotFoundException('The cube does not exist');
        }
        
        return $this->render('cube/show.html.twig', [
            'cube' => $cube
        ]);
    }
}