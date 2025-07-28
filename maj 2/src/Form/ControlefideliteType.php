<?php

namespace App\Form;

use App\Entity\Controlefidelite;
use App\Entity\Instrument;
use App\Repository\InstrumentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ControlefideliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void

    {
        $ide = $options['ide']; // Récupération de l'ID de l'entreprise si passé dans les options
        $instrument = $options['instrument'];
        $builder
            // ->add('pointsdessai',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ],
            //     'mapped' => false,
            // ])
            // ->add('valeurnominale',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ],
            //     'mapped' => false,
            // ])
            // ->add('indicationlue',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ],
            //     'mapped' => false,
            // ])
            // ->add('ecartreleve',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ],
            //     'mapped' => false,
            // ])
            // ->add('ecartmaximalreleve',null,[
            //     'attr' =>[
            //         'class' => 'form-control',
            //     ],
            //     'mapped' => false,
            // ])
            ->add('instrument', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'codeinstrument',
                'placeholder' => 'Sélectionner un instrument',
                'required' => true,
                'choices' => $instrument,
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
