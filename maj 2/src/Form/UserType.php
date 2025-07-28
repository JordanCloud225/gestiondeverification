<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('email', EmailType::class,[
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('contact',null,[
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('nom', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Regex([
                            'pattern' => '/^[0-9a-zA-Z-\s\'ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ]+$/',
                            'match' => true,
                            'message' => 'sont seulement acceptés: les chiffres, les lettres minuscules et majuscules avec ou sans accents, les espaces, les tirets et les apostrophes',
                                ])
                    ],
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('roles', ChoiceType::class, [
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => [
                        'Utilisateur' => 'ROLE_USER',
                        'Propriétaire' => 'ROLE_PROPRIO',
                    ],
                    'attr' => [
                        'class' => 'form-control'
                    ]

                ])
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'required' => true,
                    'first_options' => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmation'],
                    'invalid_message' => 'Les champs mot de passe doivent être identiques',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 4,
                            'minMessage' => 'Mot de passe doit comporter 4 caractères au moins',
                                ]),
                    ],
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                // ->add('brochure', DropzoneType::class, [
                //     'required' => false, 
                //     'mapped' => false,
                //     'attr' => [
                //         'placeholder' => 'Cliquer pour telecharger ou deplacer puis deposer votre image',
                //     ],
                // ]) 

        ;

        $builder->get('roles')
                ->addModelTransformer(new CallbackTransformer(
                                function ($rolesArray) {
// transform the array to a string
                                    return count($rolesArray) ? $rolesArray[0] : null;
                                },
                                function ($rolesString) {
// transform the string back to an array
                                    return [$rolesString];
                                }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

}