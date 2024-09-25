<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextareaType::class)
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                    new Regex(
                        [
                            'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                            'message' => 'L\'adresse email n\'est pas valide'
                        ]
                    )
                ]
            ])
            ->add('code', TextareaType::class,
            [
                'required' => false,
            ])
            // mettre un required a false
            ->add('visible', CheckboxType::class,
            [
                'required' => false,
            ])

            ->add('plainPassword', PasswordType::class, [
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                    new Length([
                        'min' => 8,
                        'max' => 30,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le mot de passe doit contenir au maximum {{ limit }} caractères'
                    ]),
                    new Regex([
                        'pattern' => '#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#',
                        'message' => 'Le mot de passe doit contenir au moins une minuscule, une majuscule et un chiffre'
                    ])
                ],
                'mapped' => false

//        assert not blank, not null, length min 8, max 30
            ])
            ->add('profile', FileType::class, [
                'required' => false,
                'constraints' => [
                    // aille maximum 10 mégaoctets, formats autorisés : jpg, et png. Configurez des messages d’erreurs dans le cas où la taille n’est pas respectée (maxSizeMessage) ou que le format n’est pas respecté (extensionsMessage).
                    new File([
                        'maxSize' => '10M',
                        'maxSizeMessage' => 'La taille du fichier ne doit pas dépasser {{ limit }} {{ suffix }}',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Le fichier doit être de type {{ types }}'
                    ])
                ],
                'mapped' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
