<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', null, [
                'attr' => ['class' => 'form-control text-input'],
                'label' => 'Email',
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Email()
                ],
                'required' => true
                ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'password-input',
                    'id' => 'user_register_password',
                ],
                'label' => 'Mot de passe',
                'required' => true
            ])
            ->add('firstName', null, [
                'attr' => ['class' => 'form-control text-input'],
                'label' => 'Prénom',
                'required' => true
            ])
            ->add('lastName', null, [
                'attr' => ['class' => 'form-control text-input'],
                'label' => 'Nom',
                'required' => true
            ])
            ->add('pseudo', null, [
                'attr' => ['class' => 'form-control text-input'],
                'label' => 'Pseudo',
                'required' => true
            ])
            /*
            ->add('birth', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Naissance',
                'required' => true
            ])
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
