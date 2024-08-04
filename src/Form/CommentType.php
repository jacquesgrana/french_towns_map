<?php

namespace App\Form;

use App\Entity\Comment;
use App\Entity\Town;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('comment')
            ->add('createdAt', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Création',
            ])
            ->add('modifiedAt', null, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Modification',
            ])
            ->add('score')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'pseudo',
            ])
            ->add('town', EntityType::class, [
                'class' => Town::class,
                'choice_label' => 'townName',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
