<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Designation;
use App\Entity\Instrument;
use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Tolerance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstrumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('designation', EntityType::class, [
            'class' => Designation::class,
            'choice_label' => 'libelle',
            'attr' =>[
                'class' => 'form-control'
            ]
        ]) 
        ->add('marque', EntityType::class, [
            'class' => Marque::class,
            'choice_label' => 'libelle',
            'attr' =>[
                'class' => 'form-control'
            ]
        ]) 
        ->add('modele', EntityType::class, [
            'class' => Modele::class,
            'choice_label' => 'libelle',
            'attr' =>[
                'class' => 'form-control'
            ]
        ]) 
            ->add('numeroserie',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('porteemax',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('porteemini',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('classeprecision',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('echelonverification',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nom',
                'attr' =>[
                    'class' => 'form-control'
                ]
            ])  
            ->add('tolerance', EntityType::class, [
                'class' => Tolerance::class,
                'choice_label' => 'valeur',
                'attr' =>[
                    'class' => 'form-control'
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Instrument::class,
            'csrf_protection' => false,

        ]);
    }
}
