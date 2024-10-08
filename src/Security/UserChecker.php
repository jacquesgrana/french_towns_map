<?php
namespace App\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\MailerService;


class UserChecker implements UserCheckerInterface
{

    public function __construct(
        private MailerService $mailer
    )
    {}

    public function checkPreAuth(UserInterface $user): void
    {
        /** @var \App\Entity\User $user **/
        if (!$user->isActive()) {
            $this->mailer->GenerateAndSendConfirmRegisterEmail($user);
            throw new CustomUserMessageAuthenticationException('Votre compte n\'est pas actif. Un email de confirmation vous a été envoyé. Veuillez cliquer sur le lien contenu par l\'email pour activer votre compte.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
?>