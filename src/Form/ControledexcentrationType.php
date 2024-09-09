<?php

namespace App\Form;

use App\Entity\Controledexcentration;
use App\Entity\Instrument;
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

        $builder
            ->add('numposition',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('valeurnominale',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('indicationlue',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('indicationsurcharge',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('ecartreleve',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('croquisetposition',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('excentrationcorrecte',ChoiceType::class,[
                'label' => 'Excentration correcte ?',
                'mapped' => false,
                'choices' =>[
                    'oui' => 'oui',
                    'non' => 'non',
                ],
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('instrument', EntityType::class, [
                'class' => Instrument::class,
                'choices' => $instrument,
                'choice_label' => 'codeinstrument',
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Controledexcentration::class,
            'csrf_protection' => false,
            'instrument' => null,
        ]);
    }
}
