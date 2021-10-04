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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class AddUserType extends AbstractType
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
            ->add('password', RepeatedType::class, [
                'type'=>PasswordType::class,
                'attr' => ['autocomplete' => 'new-password', 'class'=>'form-control'],
                'first_options'=> ['label' => 'Mot de passe', 'attr' => ['class' => 'form-control mb-3']],
                'second_options'=>['label' => 'Confirmez le mot de passe', 'attr' => ['class' => 'form-control mb-3']],
                'invalid_message'=>'Les champs ne sont pas identiques',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères, chiffres ET lettres',
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
            ->add('contrat', ChoiceType::class, [
                'choices'=>[
                    'CDI'=>'CDI',
                    'CDD'=>'CDD',
                    'Intérim'=>'Intérim'
                ],
                'expanded'=>true,
                'multiple'=>false
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
