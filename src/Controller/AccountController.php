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

}