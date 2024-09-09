<?php

namespace App\Form;

use App\Entity\Controlejustesse;
use App\Entity\Instrument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControlejustesseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $instrument = $options['instrument'];
        $builder
            ->add('pointsdessai',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('valeurnominale',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('valeurmonte',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('valeurdescente',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('indicationsurchage',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('ecartreleve',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('justessecorrecte',ChoiceType::class,[
                'label' => 'Justesse correcte ?',
                'choices' =>[
                    'oui' => 'oui',
                    'non' => 'non',
                ],
            ])
            ->add('sensibilitecorrecte',ChoiceType::class,[
                'label' => 'Sensibilité correcte ?',
                'choices' =>[
                    'oui' => 'oui',
                    'non' => 'non',
                ],
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
            'data_class' => Controlejustesse::class,
            'csrf_protection' => false,
            'instrument' => null,

        ]);
    }
}
