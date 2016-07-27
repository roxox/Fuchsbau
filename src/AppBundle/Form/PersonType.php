<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PersonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('geschlecht', EntityType::class,
                [
                    'class' => 'AppBundle:Geschlecht',
                    'choice_label' => 'name',
                    'placeholder' => '',
                    'attr' => ['data-cat-form-read-only' => true]
                ]
            )
            ->add('titel', EntityType::class,
                [
                    'class' => 'AppBundle:PersonenTitel',
                    'choice_label' => 'name',
                    'placeholder' => '',
                    'required' => false,
                    'attr' => ['data-cat-form-read-only' => true]
                ]
            )
            ->add('vorname', TextType::class)
            ->add('nachname', TextType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Person',
            )
        );
    }
}