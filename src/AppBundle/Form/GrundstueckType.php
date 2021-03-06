<?php
namespace AppBundle\Form;

use AppBundle\Entity\Adresse;
use AppBundle\Entity\Email;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class GrundstueckType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'bundesland',
                EntityType::class,
                [
                    'class' => 'AppBundle:Bundesland',
                    'choice_label' => 'name',
                    'label' => 'Bundesland',
                    'placeholder' => '',
                    'attr' => ['data-cat-form-read-only' => false]
                ]
            )
            ->add('strasse', TextType::class)
            ->add('hausnummer', TextType::class)
            ->add(
                'zusatz',
                TextType::class,
                [
                    'required' => false
                ]
            )
            ->add('postleitzahl', IntegerType::class)
            ->add('ort', TextType::class)
            ->add(
                'groesse',
                TextType::class,
                [
                    'required' => false
                ]
            )
            ->add(
                'kaufpreis',
                TextType::class,
                [
                    'required' => false
                ]
            )
            ->add(
                'erschiessungskostenanteil',
                TextType::class,
                [
                    'required' => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Grundstueck',
            )
        );
    }
}