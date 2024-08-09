<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Library\CustomLibrary;
use App\Entity\User;
use App\Repository\TokenCategoryRepository;
use App\Repository\TokenRepository;
use App\Entity\TokenCategory;
use App\Entity\Token;
use App\Service\TokenService;
use App\Repository\UserRepository;


class MailerService
{
    private $mailer;
    private $tokenStorage;
    private $entityManager;
    private $userRepository;
    private $tokenRepository;
    private $router;
    private $tokenService;
    private $tokenCategoryRepository;


    public function __construct(
        TokenStorageInterface $tokenStorage, 
        MailerInterface $mailer, 
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        TokenRepository $tokenRepository,
        TokenCategoryRepository $tokenCategoryRepository,
        RouterInterface $router,
        TokenService $tokenService
        )
    {
        $this->tokenStorage = $tokenStorage;
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->router = $router;
        $this->tokenService = $tokenService;
        $this->tokenCategoryRepository = $tokenCategoryRepository;
    }

    public function GenerateAndSendConfirmRegisterEmail($user): string {

        $routeName = 'app_confirm_register'; 
        $title = 'Confirmation de l\'adresse email';
        $paragraph = 'Ceci est un email pour vous permettre de valider votre adresse email. Le lien ci-dessous vous permettra de la valider et, ensuite, de l\'utiliser pour vous connecter.';
        //return '';
        return $this->generateAndSendMailWithLink($user, $routeName, $title, $paragraph);
    }

    
    public function generateAndSendMailWithLink($user, $routeName, $title, $paragraph): string {
        
        $email = $user->getEmail();
        $pseudo = $user->getPseudo();
        $subject = 'Email de confirmation pour ' . $pseudo;
        $newTokenValue = $this->tokenService->generateToken('CONFIRM_NEW_USER');
        $token = new Token();
        $token->setUser($user);
        $token->setToken($newTokenValue);
        // set token category
        $token->setCategory($this->tokenCategoryRepository->findOneBy(['name' => 'CONFIRM_NEW_USER']));
        $token->setExpireAt(new \DateTimeImmutable('+1 day'));
        $user->addToken($token);
        $this->entityManager->persist($token);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // ajouter url au context du template de l'email
        $url = $this->router->generate($routeName, ['token' => $newTokenValue], UrlGeneratorInterface::ABSOLUTE_URL);
        $emailToSend = (new TemplatedEmail())
            ->from(new Address('inbox.test.jac@free.fr'))
            ->to($email)
            ->subject($subject)
            ->htmlTemplate('mail/send_confirm_register.html.twig')
            ->context([
                'pseudo' => $pseudo,
                'url' => $url,
                'title' => $title,
                'paragraph' => $paragraph,
        ]);
        try {
            $this->mailer->send($emailToSend);
            return 'Email ok';
        } 
        catch (TransportExceptionInterface $e) {
            return 'Email ko : ' . $e->getMessage();
        }
        return 'Email ok';
    }

}