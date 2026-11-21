<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Cube;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class FavoriteController extends AbstractController
{
    #[Route('/favorites', name: 'app_favorite_index', methods: ['GET'])]
    public function index(): Response
    {
        $member = $this->getUser();

        return $this->render('favorite/index.html.twig', [
            'favoriteCubes' => $member->getFavoriteCubes(),
        ]);
    }
    
    
    #[Route('/favorites/add/{id}', name: 'app_favorite_add', methods: ['POST'])]
    public function add(
        Cube $cube,
        Request $request,
        EntityManagerInterface $entityManager
        ): Response {
            $member = $this->getUser();
            
            if (!$this->isCsrfTokenValid(
                'favorite_add' . $cube->getId(),
                $request->getPayload()->getString('_token')
                )) {
                    throw $this->createAccessDeniedException('Invalid CSRF token.');
                }
                
                // A cube must be in a public gallery belonging to another member
                $canFavorite = false;
                
                foreach ($cube->getCubeGalleries() as $gallery) {
                    if ($gallery->isPubliee() && $gallery->getMember() !== $member) {
                        $canFavorite = true;
                        break;
                    }
                }
                
                if (!$canFavorite) {
                    throw $this->createAccessDeniedException(
                        'This cube cannot be added to your favorites.'
                        );
                }
                
                $member->addFavoriteCube($cube);
                $entityManager->flush();
                
                $this->addFlash('success', 'Cube added to your favorites!');
                
                return $this->redirectToRoute('app_favorite_index');
    }
    
    
    #[Route('/favorites/remove/{id}', name: 'app_favorite_remove', methods: ['POST'])]
    public function remove(
        Cube $cube,
        Request $request,
        EntityManagerInterface $entityManager
        ): Response {
            $member = $this->getUser();
            
            if (!$this->isCsrfTokenValid(
                'favorite_remove' . $cube->getId(),
                $request->getPayload()->getString('_token')
                )) {
                    throw $this->createAccessDeniedException('Invalid CSRF token.');
                }
                
                $member->removeFavoriteCube($cube);
                $entityManager->flush();
                
                $this->addFlash('success', 'Cube removed from your favorites!');
                
                return $this->redirectToRoute('app_favorite_index');
    }
    
}
