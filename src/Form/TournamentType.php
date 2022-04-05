<?php

namespace App\Form;

use App\Entity\Tournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\LessThan;

class TournamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('starting_date', DateTimeType::class, [
                'date_label' => 'Starts On',
            ])
            ->add('ending_date', DateTimeType::class, [
                'date_label' => 'Starts On',
                'constraints' => [
                    new GreaterThan([
                        'propertyPath' => 'parent.all[starting_date].data'
                    ]),
                    /*new LessThan([
                        'propertyPath' => 'parent.all[starting_date].data'
                    ])*/
                ]
            ])
            ->add('Award', MoneyType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tournament::class,
        ]);
    }
}
