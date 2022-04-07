<?php

namespace App\Form;

use App\Entity\Game;
use App\Entity\Tournament;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            ->add('winner', CollectionType::class,
                ['entry_type' => User::class,
                    'entry_options' => ['label' => false],
                ])
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
