<?php

namespace App\Form;

use App\Entity\Intervention;
use App\Entity\Technicien;
use App\Repository\InterventionRepository;
use App\Repository\TechnicienRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresenceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ide = $options['ide']; // Récupération de l'ID de l'entreprise si passé dans les options
        $builder
            // ->add('present')
            ->add('intervention', EntityType::class, [
                'class' => Intervention::class,
                'choice_label' => "'demandetravaux'",
                'required' => true,
                'query_builder' => function(InterventionRepository $int) use ($ide)
                {
                return $int->findbydeletedAt($ide);
                }
            ])
            ->add('technicien', EntityType::class, [
                'class' => Technicien::class,
                'choice_label' => 'nometprenom',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function(TechnicienRepository $tech) use ($ide)
                {
                return $tech->findbydeletedAt($ide);
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'ide' => null, // Option pour l'ID de l'entreprise
        ]);
    }
}
