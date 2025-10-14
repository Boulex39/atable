<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('ingredients')
            ->add('steps')
            ->add('prepTime')
            ->add('cookTime')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'id',
            ])
        ;

        /*
        ===========================================================
        🔒 Champs techniques désactivés pour le moment :
        - createdAt : sera géré automatiquement dans l’entité
        - publishedAt : sera ajouté plus tard (validation admin)
        - author : défini automatiquement à l’utilisateur connecté
        ===========================================================

        ->add('createdAt', null, [
            'widget' => 'single_text',
        ])
        ->add('publishedAt', null, [
            'widget' => 'single_text',
        ])
        ->add('author', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'id',
        ])
        */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}

