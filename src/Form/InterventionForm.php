<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Intervention;
use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Site;
use App\Entity\Technicien;
use App\Entity\Typeinstrument;
use App\Repository\ClientRepository;
use App\Repository\MarqueRepository;
use App\Repository\ModeleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ide = $options['ide']; // Récupération de l'ID de l'entreprise si passé dans les options
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'nom',
                'choice_value' => 'id',
                'attr' =>[
                    'class' => 'form-control'
                ],
                'required' => true,
                'placeholder' => 'Sélectionner un client',
                'query_builder' => function(ClientRepository $cli) use ($ide)
                {
                return $cli->findbydeletedAt($ide);
                }
            ])
            ->add('adresse', null, [
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('site', null, [
                'attr' =>[
                'class' => 'form-control'
                ]
            ])
            ->add('interlocuteur', null, [
                'attr' =>[
                'class' => 'form-control'
                ]
            ])
            ->add('contactinterlocuteur', null, [
                'required' => false,
                'attr' =>[
                'class' => 'form-control'
                ]
            ])
            ->add('typedequipement', EntityType::class, [
                'class' => Typeinstrument::class,
                'choice_label' => 'libelle',
                'choice_value' => 'id',
                'attr' =>[
                'class' => 'form-control'
                ],
                'required' => true,
                'placeholder' => 'Sélectionner un type d\'instrument',
            ])
            ->add('marque', EntityType::class, [
            'class' => Marque::class,
            'choice_label' => 'libelle',
            'choice_value' => 'id',
            'attr' =>[
                'class' => 'form-control'
            ],
            'placeholder' => 'Sélectionner une marque',
            'query_builder' => function(MarqueRepository $marq) use ($ide)
            {
                return $marq->findbydeletedAt($ide);
            }
        ]) 
        ->add('modele', EntityType::class, [
            'class' => Modele::class,
            'choice_label' => 'libelle',
            'choice_value' => 'id',
            'attr' =>[
                'class' => 'form-control'
            ],
            'placeholder' => 'Sélectionner un modèle',
            'query_builder' => function(ModeleRepository $mode) use ($ide)
            {
                return $mode->findbydeletedAt($ide);
            }
        ]) 
            ->add('numserie',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('porteemaxi',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('porteemini',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('echelonunite',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('heure',TimeType::class,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('dateintervention',DateType::class,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('demandetravaux',TextareaType::class,[
                'attr' =>[
                    'rows' => 5, 
                    'class' => 'form-control',
                ]
            ])
            ->add('detailtravaux',TextareaType::class,[
                'attr' =>[
                    'rows' => 10, 
                    'class' => 'form-control',
                ]
            ])
            ->add('observationclient',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('equipement',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('quantiteequipement',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('signataire',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('signature',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            // ->add('valider',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class, // Replace with your actual entity class
            'csrf_protection' => false,
            'ide' => null, // Option to pass the enterprise ID
        ]);
    }
}
