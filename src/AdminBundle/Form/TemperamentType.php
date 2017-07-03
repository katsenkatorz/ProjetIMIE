<?php

namespace AdminBundle\Form;


use AdminBundle\Entity\Temperament;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TemperamentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name", TextType::class, ["label" => "Nom du type"])
            ->add("temperament", TextType::class, ["label" => "Tempérament"])
            ->add("opposedTemperament", TextType::class, ["label" => "Tempérament opposés"])
            ->add("save", SubmitType::class, ["label" => "Créer un tempérament"])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Temperament::class,
            "csrf_protection" => true,
            "csrf_field_name" => '_token',
            "csrf_token_id" => "temperament_item",
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_personnalitytype';
    }


}
