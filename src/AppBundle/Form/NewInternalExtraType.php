<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NewInternalExtraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Name',
                    'required' => false
                ]
            )
            ->add('anzahl', TextType::class)
            ->add('kosten_ist', TextType::class)
            ->add('kosten_plan', TextType::class)
            ->add(
                'einheit',
                EntityType::class,
                [
                    'class' => 'AppBundle:Einheit',
                    'choice_label' => 'bezeichnung',
                    'label' => 'Einheit',
                    'placeholder' => '',
                    'attr' => ['data-cat-form-read-only' => false]
                ]
            )
            ->add('rollenGroup', EntityType::class,
                [
                    'class' => 'AppBundle:RollenGroup',
                    'choice_label' => 'name',
                    'label' => 'Gruppierung',
                    'placeholder' => '',
                    'required' => false,
                    'attr' => ['data-cat-form-read-only' => true]
                ]
            )
            ->add(
                'mehrwertsteuer',
                EntityType::class,
                [
                    'class' => 'AppBundle:Mehrwertsteuer',
                    'choice_label' => 'bezeichnung',
                    'label' => 'Mehrwertsteuer',
                    'placeholder' => '',
                    'attr' => ['data-cat-form-read-only' => false]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Rolle',
            )
        );
    }
}