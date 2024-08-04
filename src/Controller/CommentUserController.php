<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\TownRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;




class CommentUserController extends AbstractController
{
    #[Route('/submit-new-comment', name: 'submit_new_comment', methods: ['POST'])]
    public function submitNewComment(
        EntityManagerInterface $em, 
        Request $request,
        TownRepository $townRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['_title'];
        $comment = $data['_comment'];
        $score = $data['_score'];
        $townId = $data['_town_id'];
        $csrfToken = $data['_csrf_token'];

        // verifier le token CSRF
        if (!$this->isCsrfTokenValid('comment-form', $csrfToken)) {
            dd('CSRF token invalid');
            return $this->redirectToRoute('app_home');
        }
        /** @var \App\Entity\User $user **/
        $user = $this->getUser();

        if(!$user) {
            dd('user not logged in');
            return $this->redirectToRoute('app_home');
        }

        $town = $townRepository->find($townId);
        if(!$town) {
            dd('town not found');
            return $this->redirectToRoute('app_home');
        }

        // set les dates
        $createdAt = new \DateTimeImmutable();
        $modifiedAt = new \DateTimeImmutable();

        $newComment = new Comment();
        $newComment->setTitle($title);
        $newComment->setComment($comment);
        $newComment->setCreatedAt($createdAt);
        $newComment->setModifiedAt($modifiedAt);
        $newComment->setScore($score);
        $newComment->setUser($user);
        $newComment->setTown($town);

        $em->persist($newComment);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }


    #[Route('/submit-updated-comment', name: 'submit_updated_comment', methods: ['POST'])]
    public function submitUpdatedComment(
        CommentRepository $commentRepository,
        EntityManagerInterface $em,
        Request $request,
        ): Response 
    {
        $data = json_decode($request->getContent(), true);
        $commentId = $data['_comment_id'];
        $title = $data['_title'];
        $comment = $data['_comment'];
        $score = $data['_score'];
        $csrfToken = $data['_csrf_token'];

        if (!$this->isCsrfTokenValid('comment-form', $csrfToken)) {
            dd('CSRF token invalid');
            return $this->redirectToRoute('app_home');
        }

        $updatedComment = $commentRepository->find($commentId);
        if(!$updatedComment) {
            dd('comment not found');
            return $this->redirectToRoute('app_home');
        }

        $updatedComment->setTitle($title);
        $updatedComment->setComment($comment);
        $updatedComment->setScore($score);
        $updatedComment->setModifiedAt(new \DateTimeImmutable());
        //$em->persist($comment);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }


    #[Route('/delete-comment', name: 'delete_comment', methods: ['POST'])]
    public function deleteComment(
        CommentRepository $commentRepository,
        EntityManagerInterface $entityManager,
        Request $request
        ): Response
    {
        /** @var \App\Entity\User $user **/
        $user = $this->getUser();

        if(!$user) {
            dd('user not logged in');
            return $this->redirectToRoute('app_home');
        }
        $data = json_decode($request->getContent(), true);
        $commentId = $data['commentId'];
        $comment = $commentRepository->find($commentId);

        if(!$comment) {
            //dd('comment not found');
            return $this->redirectToRoute('app_home');
        }
        if($comment->getUser() !== $user) {
            //dd('user not authorized');
            return $this->redirectToRoute('app_home');
        }

        $entityManager->remove($comment);
        $entityManager->flush();
        return $this->redirectToRoute('app_home');
    }
}