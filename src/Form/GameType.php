<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $players = $options['user_choice'] ?? null;
        /*dd($players);*/

        $builder
            ->add('Starting_date')
            ->add('ending_date')
            ->add('winner', EntityType::class,
                ['class' => User::class,
                'choice_label' => $players->getPseudo(),
                'label' => 'Joueur : '])
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
            'user_choice' => User::class,
        ]);
    }
}
