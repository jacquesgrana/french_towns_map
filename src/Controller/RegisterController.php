<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserRegisterType;
use App\Service\MailerService;
use App\Repository\TokenRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validation;
use Doctrine\ORM\EntityManagerInterface;

class RegisterController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        MailerService $mailerService
    ): Response
    {
        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newUser = $form->getData();
            $email = $newUser->getEmail();
            $pseudo = $newUser->getPseudo();
            $validator = Validation::createValidator();
            $violations = $validator->validate($email, new Email());

            $isUniqueEmail = $userRepository->findOneByEmail($email) === null;
            $isUniquePseudo = $userRepository->findOneByPseudo($pseudo) === null;
            $isValidEmail = 0 === count($violations);

            if (!$isUniqueEmail || !$isUniquePseudo || !$isValidEmail) {
                if(!$isUniqueEmail) {
                    //$form->get('email')->addError(new \Symfony\Component\Form\FormError('Email non unique'));
                    $this->addFlash('error', 'Email non unique');
                }
                if(!$isUniquePseudo) {
                    //$form->get('pseudo')->addError(new \Symfony\Component\Form\FormError('Pseudo non unique'));
                    $this->addFlash('error', 'Pseudo non unique');
                }
                if(!$isValidEmail) {
                    //$form->get('email')->addError(new \Symfony\Component\Form\FormError('Email invalide'));
                    $this->addFlash('error', 'Email invalide');
                }
                return $this->redirectToRoute('app_register', [], Response::HTTP_SEE_OTHER);
            }
            else {
                // récupérer le mot de passe
                $passwordToHash = $newUser->getPassword();
                $passwortHashed = password_hash($passwordToHash, PASSWORD_BCRYPT);
                $user->setPassword($passwortHashed);

                $user->setRoles(['ROLE_USER']);
                $user->setActive(false);

                $createdAt = new \DateTimeImmutable();
                $modifiedAt = new \DateTimeImmutable();

                $user->setCreatedAt($createdAt);
                $user->setModifiedAt($modifiedAt);

                $entityManager->persist($user);
                $entityManager->flush();

                
                $result = $mailerService->GenerateAndSendConfirmRegisterEmail($user);
                //dd($result);
                if ($result === 'Email ok') {
                    $this->addFlash('success', 'Email de confirmation envoyé');
                }
                else {
                    $this->addFlash('error', $result);
                }
                
                return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('register/register.index.html.twig', [
            'controller_name' => 'RegisterController',
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/register/confirm', name: 'app_confirm_register', methods: ['GET'])]
    public function confirm(
        Request $request,
        TokenRepository $tokenRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $tokenCategory = 'CONFIRM_NEW_USER';
        $isActivated = false;
        $errorText = '';
        $pseudo = '';
        $tokenValue = $request->query->get('token');
        $confirmToken = $tokenRepository->findOneBy(['token' => $tokenValue]);
        $confirmToken = $confirmToken->getCategory()->getName() === $tokenCategory ? $confirmToken : null;

        if ($confirmToken === null) {
            $errorText = 'Token introuvable.';
            $isActivated = false;
            //$userId = -1;
        }
        else if ($confirmToken->getExpireAt() < new \DateTimeImmutable()) {
            $errorText = 'Token expiré.';
            $isActivated = false;
            $entityManager->remove($confirmToken);
            $entityManager->flush();
            //$userId = $confirmToken->getUser()->getId();
        }
        else {
            $isActivated = true;
            $user = $confirmToken->getUser();
            $pseudo = $user->getPseudo();
            $user->setActive(true);
            //$userId = $user->getId();
            //$user->removeConfirmToken($confirmToken);
            foreach ($user->getTokens() as $ct) {
                if($ct->getCategory()->getName() === $tokenCategory) {
                    $user->removeToken($ct);
                    $entityManager->remove($ct);
                }
            }
            $entityManager->persist($user);
            //$entityManager->remove($confirmToken);
            $entityManager->flush();
        }       

        return $this->render('register/confirm_register.html.twig', [
            'controller_name' => 'SignInController',
            'pseudo' => $pseudo,
            'isActivated' => $isActivated,
            'errorText' => $errorText,
            //'userId' => $userId
        ]);
    }

}