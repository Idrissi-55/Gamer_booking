<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Starting_date')
            ->add('ending_date')
            ->add('winner')
            ->add('defeated')
            ->add('description')
            ->add('tournament', EntityType::class,
            ['class' => Tournament::class,
                'choice_label' => 'title',
                'label' => 'Tournoi : '])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
