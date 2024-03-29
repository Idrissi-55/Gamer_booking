<?php

namespace App\Form;

use App\Entity\Tournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre : ', 'attr' => ['placeholder' => 'Titre']])
            ->add('starting_date', DateTimeType::class, [
                'date_label' => 'Starts On',
                'widget'=>'single_text',
                'label' => 'Date de début : '
            ])
            ->add('ending_date', DateTimeType::class, [
                'date_label' => 'Ends On',
                'widget'=>'single_text',
                'label' => 'Date de fin : '
            ])
            ->add('Description', TextareaType::class, ['label' => 'Description : ', 'attr' => ['placeholder' => 'Description']])
            ->add('Award', MoneyType::class,  ['label' => 'Récompense : ', 'attr' => ['placeholder' => '99']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
    }
}
