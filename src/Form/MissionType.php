<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Mission;
use App\Entity\Statut;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Range;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('description')
            ->add('statut', ChoiceType::class, [
                'choices'=> [
                    'En attente' => Statut::PENDING,
                    'En cours'=> Statut::IN_PROGRESS,
                    'Terminer'=> Statut::COMPLETED,
                    'Echouée'=> Statut::FAILED,
                ],
                'choice_label' => function ($choices, $key, $value) {
                    return $choices->value;
                },

                'choice_value' => function (?Statut $statut ) {
                    return $statut ? $statut->value : null;
                }
            ])
            ->add('debut', null, [
                'widget' => 'single_text',
            ])
            ->add('fin', null, [
                'widget' => 'single_text',
            ])
            ->add('localisation')
            ->add('niveauDanger', IntegerType::class, [
                'attr' => [
                    'min' => 1,
                    'max' => 10,
                ],
                'constraints' => [
                    new Range([
                        'min' => 1,
                        'max' => 10,
                        'notInRangeMessage' => 'Le niveau d\'énergie doit être compris entre {{ min }} et {{ max }}',
                    ]),
                ]
            ])
            ->add('equipe', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
