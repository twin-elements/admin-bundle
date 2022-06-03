<?php

namespace TwinElements\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use TwinElements\AdminBundle\Entity\AdminUser;
use TwinElements\Component\AdminTranslator\AdminTranslator;
use TwinElements\FormExtensions\Type\SaveButtonsType;

class AdminUserPasswordType extends AbstractType
{
    private AdminTranslator $translator;

    public function __construct(AdminTranslator $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'admin.actual_password',
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->translate('admin.not_similar_error'),
                'required' => true,
                'first_options' => [
                    'label' => $this->translator->translate('admin.password')
                ],
                'second_options' => [
                    'label' => $this->translator->translate('admin.repeat_password')
                ],
                'options' => [
                    'attr' => [
                        'autocomplete' => 'new-password'
                    ]
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('save', SaveButtonsType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdminUser::class
        ]);
    }
}
