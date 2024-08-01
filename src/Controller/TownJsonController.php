<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TownRepository;
use App\Repository\CommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;


class TownJsonController extends AbstractController
{

    #[Route('/get-towns-by-bounds', name: 'get_towns_by_bounds', methods: ['POST'])]
    public function getTownsByBounds(
        TownRepository $townRepository,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $sw_lat = $data['sw_lat'];
        $sw_lng = $data['sw_lng'];
        $ne_lat = $data['ne_lat'];
        $ne_lng = $data['ne_lng'];

        return new JsonResponse($townRepository->findByBoundingBox($sw_lat, $sw_lng, $ne_lat, $ne_lng));
    }


    #[Route('/get-towns-by-name', name: 'get_towns_by_name', methods: ['POST'])]
    public function getTownsByName(
        TownRepository $townRepository,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $searchRequest = $data['searchRequest'];

        return new JsonResponse($townRepository->findByName($searchRequest));
    }

    #[Route('/get-is-favorite', name: 'get_is_favorite', methods: ['POST'])]
    public function getIsFavorite(
        TownRepository $townRepository,
        Request $request
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (!isset($data['townId'])) {
                return new JsonResponse(['error' => 'townId is required'], 400);
            }
    
            $townId = $data['townId'];
            $town = $townRepository->find($townId);
            if ($town === null) {    
                return new JsonResponse(['isFavorite' => false]);
            }
    
            /** @var \App\Entity\User $user **/
            $user = $this->getUser();
            // force user type to User entity

            if ($user === null) {
                return new JsonResponse(['isFavorite' => false]);
            }

            $userFavorites = $user->getFavoriteTowns();
            if (count($userFavorites) === 0) {
                return new JsonResponse(['isFavorite' => false]);
            }
            $isFavorite = $userFavorites->contains($town);
            return new JsonResponse(['isFavorite' => $isFavorite]);
        } 
        catch (\Exception $e) {
            // Log l'erreur
            //$this->logger->error('Error in getIsFavorite: ' . $e->getMessage());
            return new JsonResponse(['error' => 'An unexpected error occurred'], 500);
        }
    }

    // /toggle-favorite-for-town
    #[Route('/toggle-favorite-for-town', name: 'toggle_favorite_for_town', methods: ['POST'])]
    public function toggleFavoriteForTown(
        TownRepository $townRepository,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            if (!isset($data['townId'])) {
                return new JsonResponse(['message' => 'townId is required'], 400);
            }
    
            $townId = $data['townId'];
            $town = $townRepository->find($townId);
            if ($town === null) {    
                return new JsonResponse(['message' => 'no town found'], 404);
            }
            /** @var \App\Entity\User $user **/
            $user = $this->getUser();
            if ($user === null) {
                return new JsonResponse(['message' => 'user not logged in'], 401);
            }
            $userFavorites = $user->getFavoriteTowns();
            $isFavorite = $userFavorites->contains($town);
    
            if ($isFavorite) {
                //$userFavorites->removeFavoriteTown($town);
                $town->removeFavoriteOfUser($user);
            } else {
                //$userFavorites->addFavoriteTown($town);
                $town->addFavoriteOfUser($user);
            }
    
            //$entityManager = $this->getDoctrine()->getManager();
            $em->persist($town);
            $em->persist($user);
            $em->flush();
    
            return new JsonResponse(['message' => "done"], 200);
        } catch (\Exception $e) {
            // Log l'erreur
            //$this->logger->error('Error in toggleFavoriteForTown: ' . $e->getMessage());
            return new JsonResponse(['message' => 'An unexpected error occurred'], 500);
        }
    }

    #[Route('/get-favorite-towns', name: 'get_favorite_towns', methods: ['GET'])]
    public function getFavoriteTowns(): JsonResponse
    {
        /** @var \App\Entity\User $user **/
        $user = $this->getUser();
        if ($user === null) {
            return new JsonResponse(['message' => 'user not logged in'], 401);
        }
        return new JsonResponse($user->getFavoriteTowns());
    }
    
    #[Route('/get-comments-by-user', name: 'get_comments_for_user', methods: ['GET'])]
    public function getCommentsForUser(): JsonResponse
    {
        /** @var \App\Entity\User $user **/
        $user = $this->getUser();
        if ($user === null) {
            return new JsonResponse(['message' => 'user not logged in'], 401);
        }
        $comments = $user->getComments();
        
        $commentsArray = array_map(function($comment) {
            return [
                'id' => $comment->getId(),
                'title' => $comment->getTitle(),
                'comment' => $comment->getComment(),
                'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
                'modifiedAt' => $comment->getModifiedAt()->format('Y-m-d H:i:s'),
                'score' => $comment->getScore(),
                'userPseudo' => $comment->getUser()->getPseudo(),
                'townName' => $comment->getTown()->getTownName()
            ];
        }, $comments->toArray());
        
        return new JsonResponse(['comments' => $commentsArray]);
    }

    ///get-comments-for-town
    #[Route('/get-comments-by-town', name: 'get_comments_for_town', methods: ['POST'])]
    public function getCommentsForTown(
        TownRepository $townRepository,
        CommentRepository $commentRepository,
        Request $request
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['townId'])) {
            return new JsonResponse(['error' => 'townId is required'], 400);
        }
        $townId = $data['townId'];
        $town = $townRepository->find($townId);
        if (!$town) {
            return new JsonResponse(['message' => 'Town not found'], 404);
        }
        
        $comments = $commentRepository->findBy(['town' => $town]);
        
        $commentsArray = array_map(function($comment) {
            return [
                'id' => $comment->getId(),
                'title' => $comment->getTitle(),
                'comment' => $comment->getComment(),
                'createdAt' => $comment->getCreatedAt()->format('Y-m-d H:i:s'),
                'modifiedAt' => $comment->getModifiedAt()->format('Y-m-d H:i:s'),
                'score' => $comment->getScore(),
                'userPseudo' => $comment->getUser()->getPseudo(),
                'townName' => $comment->getTown()->getTownName()
            ];
        }, $comments);
        
        return new JsonResponse(['comments' => $commentsArray]);
    }

    #[Route('/get-average-score-by-town', name: 'get_average_score_for_town', methods: ['POST'])]

    public function getAverageScore(
        TownRepository $townRepository,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['townId'])) {
            return new JsonResponse(['error' => 'townId is required'], 400);
        }
        $townId = $data['townId'];
        $averageScore = $townRepository->getAverageScore($townId);
        return new JsonResponse(['averageScore' => $averageScore]);
    }
    
}