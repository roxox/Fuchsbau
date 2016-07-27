<?php
namespace AppBundle\Form;

use AppBundle\Entity\Adresse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('strasse', TextType::class)
            ->add('hausnummer', TextType::class)
            ->add('postleitzahl', TextType::class)
            ->add('ort', TextType::class)
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
                'data_class' => 'AppBundle\Entity\Adresse',
            )
        );
    }
}