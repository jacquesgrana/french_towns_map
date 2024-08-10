<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UserEditAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder             
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
            ->add('birth', null, [
                'attr' => ['class' => 'form-control text-input'],
                'label' => 'Naissance',
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}