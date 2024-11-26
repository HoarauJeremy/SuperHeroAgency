<?php

namespace App\Form;

use App\Entity\SuperHero;
use App\Entity\SuperPouvoir;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuperHeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('alterEgo')
            ->add('estDisponible')
            ->add('niveauEnergie')
            ->add('biographie')
            // Ajout de l'image
            ->add('imageFile', FileType::class , [
                'label'=> 'Image',
                'required' => false,
            ])
            ->add('Pouvoir', EntityType::class, [
                'class' => SuperPouvoir::class,
                'choice_label' => 'nom',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SuperHero::class,
        ]);
    }
}
