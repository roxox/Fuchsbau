<?php
namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HausType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'haustyp',
                EntityType::class,
                [
                    'class' => 'AppBundle:Haustyp',
                    'choice_label' => 'name',
                    'label' => 'Haustyp',
                    'placeholder' => '',
                    'attr' => ['data-cat-form-read-only' => false]
                ]
            )
            ->add(
                'wohnflaecheWoFiv',
                TextType::class,
                [
                    'label' => 'nach WoFIV',
                    'required' => false
                ]
            )
            ->add(
                'wohnflaecheDin',
                TextType::class,
                [
                    'label' => 'nach DIN 283/277',
                    'required' => false
                ]
            )
            ->add(
                'kaufpreis',
                NumberType::class,
                [
                    'grouping' => true,
                    'scale' => 2,
                    'label' => 'Haus Grundpreis',
                    'required' => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Haus',
            )
        );
    }
}