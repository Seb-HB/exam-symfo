<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, ['attr'=>['class'=>'form-control mb-3', 'label'=>'Prénom']])
            ->add('lastname', TextType::class, ['attr'=>['class'=>'form-control mb-3', 'label'=>'Nom']])
            ->add('email', EmailType::class, ['attr'=>['class'=>'form-control mb-3']])
            ->add('roles', ChoiceType::class, [
                'choices'=>[
                    'RH'=>'ROLE_RH',
                    'Informatique'=>'ROLE_INFO',
                    'Comptabilité'=>'ROLE_COMPTA',
                    'Direction'=>'ROLE_DIR'
                ],
                'expanded'=>true,
                'multiple'=>false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type'=>PasswordType::class,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'class'=>'form-control'],
                'first_options'=> ['label' => 'Nouveau mot de passe', 'attr' => ['class' => 'form-control mb-3']],
                'second_options'=>['label' => 'Confirmez le mot de passe', 'attr' => ['class' => 'form-control mb-3']],
                'invalid_message'=>'Les champs ne sont pas identiques',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('image', FileType::class,[
                'mapped' =>false,
                'required'=>false,
                'constraints' =>[
                    new File([
                        'maxSize' =>'50M',
                        'mimeTypes' =>['image/*'],
                        'mimeTypesMessage'=>'Fichier non autorisé',
                        'maxSizeMessage'=>'Fichier trop volumineux'
                    ])
                ]
            ])
            ->add('dateEnd', DateType::class,[
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control mb-3 no-cdi']
            ])

        ;
        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer( 
            function ($rolesArray) {
                // transform the array to a string
                return count($rolesArray)? $rolesArray[0]: null;
            },
            function ($rolesString) {
                // transform the string back to an array
                return [$rolesString];
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
