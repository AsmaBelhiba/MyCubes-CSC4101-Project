<?php

namespace App\Controller;

use App\Repository\CubeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function show(int $id, CubeRepository $cubeRepository): Response
    {
        $cube = $cubeRepository->find($id);
        
        if (!$cube) {
            throw $this->createNotFoundException('Cube non trouvé');
        }
        
        $html = '<h1>Cube #' . $cube->getId() . '</h1>';
        $html .= '<p>Description : ' . $cube->getDescription() . '</p>';
        
        $html .= '<p>';
        $html .= '<a href="' . $this->generateUrl('cube_list') . '">';
        $html .= 'Retour à la liste des cubes';
        $html .= '</a>';
        $html .= '</p>';
        
        return new Response($html);
    }
}