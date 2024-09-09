<?php

namespace App\Form;

use App\Entity\Certification;
use App\Entity\Conditionsetalonnage;
use App\Entity\Instrument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateverification', null, [
                'widget' => 'single_text',
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('numerocertificat',null,[
                'attr' =>[
                    'class' => 'form-control',
                ],
            ])
            ->add('dateemission', null, [
                'widget' => 'single_text',
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('validite', null, [
                'widget' => 'single_text',
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('instrument', EntityType::class, [
                'class' => Instrument::class,
                'choice_label' => 'codeinstrument',
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
            ->add('conditionsetalonnage', EntityType::class, [
                'class' => Conditionsetalonnage::class,
                'choice_label' => 'norme',
                'attr' =>[
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Certification::class,
            'csrf_protection' => false,

        ]);
    }
}
