<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class EntrepriseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',null, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('siteweb',null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('accreditation',null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('norme',null, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('logo',DropzoneType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Cliquer pour telecharger ou deplacer puis deposer votre image',
                ],
            ])
            ->add('service',null, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            // ->add('submit',SubmitType::class, [
            //     'label' => 'Enregistrer',
            //     'attr' => [
            //         'class' => 'btn btn-primary',
            //     ],
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
            'csrf_protection' => false,
        ]);
    }
}
