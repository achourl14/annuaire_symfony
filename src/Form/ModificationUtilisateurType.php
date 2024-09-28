<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;

class ModificationUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('oldPassword', PasswordType::class, [
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
            ])
            ->add('newPassword', PasswordType::class, [
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
                'mapped' => false,
            ])
            ->add('visible', ChoiceType::class,
                [
                    // ajoute 2 choix : oui et non
                    'choices' => [
                        'Oui' => true,
                        'Non' => false
                    ]
                ])
            ->add('code', TextType::class,
                [
                    'required' => false,
                ])
            ->add('numTelephone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{1,10}$/',
                        'message' => 'Veuillez entrer un numéro de téléphone valide (10 chiffres maximum).',
                    ]),
                ],
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
