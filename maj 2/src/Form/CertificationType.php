<?php

namespace App\Form;

use App\Entity\Certification;
use App\Entity\Conditionsetalonnage;
use App\Entity\Instrument;
use App\Repository\ConditionsetalonnageRepository;
use App\Repository\InstrumentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CertificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ide = $options['ide']; // Récupération de l'ID de l'entreprise si passé dans les options
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
            ->add('conditionsetalonnage', EntityType::class, [
                'class' => Conditionsetalonnage::class,
                'placeholder' => 'Sélectionner une condition d\'étalonnage',
                'choice_label' => 'norme',
                'attr' =>[
                    'class' => 'form-control',
                ],
                'required' => true,
                'query_builder' => function(ConditionsetalonnageRepository $cond) use ($ide)
                {
                    return $cond->findbydeletedAt($ide);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Certification::class,
            'csrf_protection' => false,
            'ide' => null, // Option pour l'ID de l'entreprise

        ]);
    }
}
