<?php

namespace TwinElements\AdminBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use TwinElements\AdminBundle\Entity\AdminUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use TwinElements\AdminBundle\Role\RoleCollection;
use TwinElements\AdminBundle\Service\AdminTranslator;
use TwinElements\FormExtensions\Type\SaveType;
use TwinElements\FormExtensions\Type\ToggleChoiceType;

class AdminUserType extends AbstractType
{
    /**
     * @var AdminTranslator $translator
     */
    private $translator;

    /**
     * @var array
     */
    private $roles;

    public function __construct(AdminTranslator $translator, RoleCollection $roleCollection)
    {
        $this->translator = $translator;
        $this->roles = $roleCollection->getRoles();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => $this->translator->translate('admin.user.username')
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->translate('admin.not_similar_error'),
                'options' => [
                    'attr' => [
                        'class' => 'col-md-4 input'
                    ]
                ],
                'required' => false,
                'first_options' => [
                    'label' => $this->translator->translate('admin.password')
                ],
                'second_options' => [
                    'label' => $this->translator->translate('admin.repeat_password')
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => $this->translator->translate('admin.email'),
                'attr' => [
                    'class' => 'col-md-4 input'
                ]
            ])
            ->add('enabled', ToggleChoiceType::class)
            ->add('roles', ChoiceType::class, [
                'label' => $this->translator->translate('admin.roles'),
                'multiple' => true,
                'choices' => $this->roles,
                'choice_label' => function ($choice) {
                    return $this->translator->translate(strtolower('role.' . $choice));
                },
                'expanded' => true
            ])
            ->add('save', SaveType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => AdminUser::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminuser';
    }


}
