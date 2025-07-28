<?php

namespace App\Form;

use App\Entity\Controledexcentration;
use App\Entity\Instrument;
use App\Repository\InstrumentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControledexcentrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $instrument = $options['instrument'];
        $ide = $options['ide']; // Récupération de l'ID de l'entreprise si passé dans les options

        $builder
            // ->add('numposition',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ]
            // ])
            // ->add('valeurnominale',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ]
            // ])
            // ->add('indicationlue',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ]
            // ])
            // ->add('indicationsurcharge',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ]
            // ])
            // ->add('ecartreleve',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ]
            // ])
            // ->add('croquisetposition',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ]
            // ])
            // ->add('excentrationcorrecte',ChoiceType::class,[
            //     'label' => 'Excentration correcte ?',
            //     'mapped' => false,
            //     'choices' =>[
            //         'oui' => 'oui',
            //         'non' => 'non',
            //     ],
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ]
            // ])
            ->add('instrument', EntityType::class, [
                'class' => Instrument::class,
                'choices' => $instrument,
                'placeholder' => 'Sélectionner un instrument',
                'choice_label' => 'codeinstrument',
                'attr' =>[
                    'class' => 'form-control',
                ],
                'required' => true,
                'query_builder' => function(InstrumentRepository $instru) use ($ide)
                {
                return $instru->findbydeletedAt($ide);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'ide' => null, // Option pour l'ID de l'entreprise
            'instrument' => null,
        ]);
    }
}
