<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PersonnalityTypeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $builder->add("name", TextType::class, ["label" => "Nom du type"])
		    ->add("personnalityType", TextType::class, ["label" => "Type de personnalité"])
		    ->add("opposedPersonnalityType", TextType::class, ["label" => "Type de personnalité opposé"])
		    ->add("save", SubmitType::class, ["label" => "Créer un type de personnalité"])
	    ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\PersonnalityType'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_personnalitytype';
    }


}
