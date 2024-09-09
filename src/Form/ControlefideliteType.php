<?php

namespace App\Form;

use App\Entity\Controlefidelite;
use App\Entity\Instrument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControlefideliteType extends AbstractType
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
            ->add('indicationlue',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('ecartreleve',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('ecartmaximalreleve',null,[
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('fidelitecorrecte',ChoiceType::class,[
                'label' => 'Fidélité correcte ?',
                'choices' =>[
                    'oui' => 'oui',
                    'non' => 'non',
                ],
            ])
            ->add('instrument', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'codeinstrument',
                'required' => true,
                'choices' => $instrument,
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Controlefidelite::class,
            'csrf_protection' => false,
            
            'instrument' => null,
        ]);
    }
}
