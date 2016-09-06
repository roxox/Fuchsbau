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
            );
//            ->add(
//                'kosten',
//                NumberType::class,
//                [
//                    'grouping' => true,
//                    'scale' => 2,
//                    'label' => 'Kosten',
//                    'required' => false
//                ]
//            )
//            ->add(
//                'mehrwertsteuer',
//                EntityType::class,
//                [
//                    'class' => 'AppBundle:Mehrwertsteuer',
//                    'choice_label' => 'bezeichnung',
//                    'label' => 'Mehrwertsteuer',
//                    'placeholder' => '',
//                    'attr' => ['data-cat-form-read-only' => false]
//                ]
//            );
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