<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;

class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'=> 'Nom du héros',
                'required' => false,
            ])
            ->add('estDisponible', CheckboxType::class, [
                'label'=> 'Disponible',
                'required' => false,
                'false_values' => [null, false],
            ])
            ->add('niveauEnergie', IntegerType::class, [
                'label'=> 'Niveau d\'énergie minimum',
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'max' => 100,
                ],
                'constraints' => [
                    new Range([
                        'min' => 0,
                        'max'=> 100,
                        'notInRangeMessage' => 'Le niveau d\'énergie doit être compris entre {{ min }} et {{ max }}'
                    ]),
                ],
            ])
            // ->add('niveauEnergie', IntegerType::class, [
            //     'label' => 'Niveau d\'énergie maximum',
            //     'required' => false,
            //     'constraints' => [
            //         new Length([
            //             'min' => 0,
            //             'max'=> 100,
            //             'minMessage' => 'Le niveau d\'énergie doit être aux minimun de {{ limit }}',
            //             'maxMessage' => 'Le niveau d\'énergie doit être aux maximum de {{ limit }}'
            //         ]),
            //     ],
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
