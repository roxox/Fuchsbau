<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TelefonnummerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('telefonTyp', EntityType::class,
                [
                    'class' => 'AppBundle:TelefonTyp',
                    'choice_label' => 'name',
                    'label' => 'Typ',
                    'placeholder' => '',
                    'attr' => ['data-cat-form-read-only' => true]
                ]
            )
            ->add('vorwahl', TextType::class)
            ->add('telefonnummer', TextType::class)
            ->add('durchwahl', TextType::class, [

                'required' => false
            ])
            ->add('privatGeschaeft', EntityType::class,
                [
                    'class' => 'AppBundle:PrivatGeschaeft',
                    'choice_label' => 'name',
                    'label' => 'Verwendung',
                    'placeholder' => '',
                    'attr' => ['data-cat-form-read-only' => true]
                ]
            )
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Telefonnummer',
            )
        );
    }
}