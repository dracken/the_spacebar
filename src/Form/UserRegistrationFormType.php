<?php

namespace App\Form;

use App\Entity\User;
use App\Form\Model\UserRegistrationFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $minLength = 5;
        $builder
            ->add('email', EmailType::class)
            //->add('password') Don't use password: avoid EVER setting that on a field that might be persisted
            ->add('plainPassword', PasswordType::class, [
                //'mapped' => false, // no longer needed when using models
                'constraints' => [
                    new NotBlank([
                       'message' => 'Password cannot be blank.'
                    ]),
                    new Length([
                       'min' => $minLength,
                       'minMessage' => 'Password must be at least '.$minLength.' characters long.'
                    ]),
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                //'mapped' => false, // no longer needed when using models
                'constraints' => [
                    new IsTrue([
                        'message' => 'You must agree to the terms to continue'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => User::class, // no longer needed when using a data model instead of Controller/View
            'data_class' => UserRegistrationFormModel::class,
        ]);
    }
}
