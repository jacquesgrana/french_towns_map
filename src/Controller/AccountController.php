<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserEditAccountType;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\MailerService;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/user/account')]
class AccountController extends AbstractController
{
    //private $tokenStorage;

    public function __construct()  // TokenStorageInterface $tokenStorage
    {
        //$this->tokenStorage = $tokenStorage;
    }

    #[Route('/', name: 'app_user_account')]
    public function index()
    {
        $user = $this->getUser();
        return $this->render('account/account.index.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/edit', name: 'app_user_account_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,     
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
        ): Response
    {
        /** @var User $user **/
        $user = $this->getUser();
        $oldPseudo = $user->getPseudo();
        $form = $this->createForm(UserEditAccountType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $userForm = $form->getData();
            // si le pseudo est modifié on vérifie que la nouvelle valeur choisie n'est pas un pseudo existant
            if ($userForm->getPseudo() !== $oldPseudo) {
                $pseudo = $userForm->getPseudo();
                $isUniquePseudo = $userRepository->findOneByPseudo($pseudo) === null;
                if (!$isUniquePseudo) {
                    $form->get('pseudo')->addError(new \Symfony\Component\Form\FormError('Pseudo non unique'));

                    return $this->render('account/account.edit.html.twig', [
                        'user' => $user,
                        'form' => $form,
                    ]);
                }
            }
            $userForm->setModifiedAt(new \DateTimeImmutable());
            $entityManager->persist($userForm);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_account', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/account.edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/edit/email', name: 'app_user_account_edit_email', methods: ['GET', 'POST'])]
    public function modifyEmail (
        ): Response
    {
        /** @var User $user **/
        $user = $this->getUser();
        // recuperer l'email a partir de la query string
        $email = $user->getEmail();
        return $this->render('account/account.edit.email.html.twig', [
            'email' => $email
        ]);
    }

    #[Route('/update/email', name: 'app_user_account_update_email', methods: ['GET', 'POST'])]
    public function setEmail (
        Request $request, 
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        MailerService $mailerService
        ): Response
    {
        /** @var \App\Entity\User $user **/
        $user = $this->getUser();
        $oldEmail = $user->getEmail();
        $email = $request->request->get('email');
        $csrf_token = $request->request->get('_csrf_token');
        $validator = Validation::createValidator();
        $violations = $validator->validate($email, new Email());
        $isUniqueEmail = $userRepository->findOneByEmail($email) === null;
        $isEmailValid = 0 === count($violations);
        $isTokenValid = $this->isCsrfTokenValid('change-email', $csrf_token);
        $isEmailsNotTheSame = $email != $oldEmail;

        if (!$isUniqueEmail || !$isEmailValid || !$isTokenValid || !$isEmailsNotTheSame) {
            if(!$isUniqueEmail) {
                $this->addFlash('error', 'Email non unique');
            }
            if(!$isEmailValid) {
                $this->addFlash('error', 'Email invalide');
            }
            if(!$isTokenValid) {
                $this->addFlash('error', 'Token invalide');
            }
            if(!$isEmailsNotTheSame) {
                $this->addFlash('error', 'Emails identiques');
            }
            return $this->redirectToRoute('app_user_account_edit_email', [], Response::HTTP_SEE_OTHER);
        }
        else {
            // TODO : améliorer : tenir compte du cas ou le nouvel email est fonctionnel ?
            $user->setEmail($email);
            $user->setActive(false);
            $entityManager->persist($user);
            $entityManager->flush();

            $result = $mailerService->GenerateAndSendConfirmRegisterEmail($user);
            //GenerateAnsSendConfirmSigninEmail
            if ($result === 'Email ok') {
                $this->addFlash('success', 'Email d\'activation envoyé');
            }
            else {
                $this->addFlash('error', $result);
            }
            return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
        }
    }
}