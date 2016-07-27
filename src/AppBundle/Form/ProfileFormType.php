<?php
namespace AppBundle\Form;

use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,
                array(
                    'label' => 'Benutzer'))
            ->add(
                'email',
                EmailType::class,
                array(
                    'label' => 'Email')
            )
            ->add('current_password', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'), array(
                'label' => 'Passwort (zum Bestätigen)',
                'translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'constraints' => new UserPassword(),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\User',
            )
        );
    }
}