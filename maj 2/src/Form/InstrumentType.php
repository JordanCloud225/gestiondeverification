<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Designation;
use App\Entity\Instrument;
use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Tolerance;
use App\Entity\Typeinstrument;
use App\Repository\ClientRepository;
use App\Repository\DesignationRepository;
use App\Repository\MarqueRepository;
use App\Repository\ModeleRepository;
use App\Repository\ToleranceRepository;
use App\Repository\TypeinstrumentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InstrumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ide = $options['ide']; // Récupération de l'ID de l'entreprise si passé dans les options
        $builder
        ->add('designation', EntityType::class, [
            'class' => Designation::class,
            'placeholder' => 'Sélectionner une désignation',
            'choice_label' => 'libelle',
            'attr' =>[
                'class' => 'form-control'
            ],
            'required' => true,
            'query_builder' => function(DesignationRepository $design) use ($ide)
            {
                return $design->findbydeletedAt($ide);
            }
        ]) 
        ->add('typeinstrument', EntityType::class, [
            'class' => Typeinstrument::class,
            'placeholder' => 'Sélectionner un type d\'instrument',
            'choice_label' => 'libelle',
            'attr' =>[
                'class' => 'form-control'
            ],
            'required' => true,
            'query_builder' => function(TypeinstrumentRepository $typinstru) use ($ide)
            {
                return $typinstru->findbydeletedAt($ide);
            }
        ])        
        ->add('marque', EntityType::class, [
            'class' => Marque::class,
            'choice_label' => 'libelle',
            'placeholder' => 'Selectionner une marque',
            'attr' =>[
                'class' => 'form-control'
            ],
            'query_builder' => function(MarqueRepository $marq) use ($ide)
            {
                return $marq->findbydeletedAt($ide);
            }
        ]) 
        ->add('modele', EntityType::class, [
            'class' => Modele::class,
            'placeholder' => 'Selectionner un modèle',
            'choice_label' => 'libelle',
            'attr' =>[
                'class' => 'form-control'
            ],
            'query_builder' => function(ModeleRepository $mode) use ($ide)
            {
                return $mode->findbydeletedAt($ide);
            }
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
            'placeholder' => 'Sélectionner un client',
            'choice_label' => 'nom',
            'attr' =>[
                'class' => 'form-control'
            ],
            'required' => true,
            'query_builder' => function(ClientRepository $cli) use ($ide)
            {
                return $cli->findbydeletedAt($ide);
            }
        ])  
        ->add('tolerance', EntityType::class, [
            'class' => Tolerance::class,
            'placeholder' => 'Sélectionner une tolérance',
            'choice_label' => 'valeur',
            'attr' =>[
                'class' => 'form-control'
            ],
            'required' => true,
            'query_builder' => function(ToleranceRepository $tole) use ($ide)
            {
            return $tole->findbydeletedAt($ide);
            }
        ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Instrument::class,
            'csrf_protection' => false,
            'ide' => null, // Option pour l'ID de l'entreprise

        ]);
    }
}
