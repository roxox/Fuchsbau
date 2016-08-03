<?php
namespace AppBundle\Form;

use AppBundle\Entity\Adresse;
use AppBundle\Entity\Email;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EmailCompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('privatGeschaeft', EntityType::class,
                [
                    'class' => 'AppBundle:PrivatGeschaeft',
                    'choice_label' => 'name',
                    'label' => 'Verwendung',
                    'placeholder' => '',
                    'attr' => ['data-cat-form-read-only' => false]
                ]
            )
            ->add(
                'emailadresse',
                TextType::class,
                array(
                    'label' => 'Email')
            )
            ->add(
                'porno',
                TextType::class,
                array(
                    'label' => 'Porno')

            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\PlainClasses\EmailCompany',
            )
        );
    }
}