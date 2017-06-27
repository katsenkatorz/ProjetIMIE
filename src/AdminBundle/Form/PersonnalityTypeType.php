<?php
namespace AdminBundle\Form;

use AdminBundle\Entity\PersonnalityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonnalityTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name", TextType::class, ["label" => "Nom du type"])
            ->add("personnalityType", TextType::class, ["label" => "Type de personnalité"])
            ->add("opposedPersonnalityType", TextType::class, ["label" => "Type de personnalité opposés"])
            ->add("save", SubmitType::class, ["label" => "Créer un type de personnalité"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => PersonnalityType::class,
            "csrf_protection" => true,
            "csrf_field_name" => '_token',
            "csrf_token_id" => "personnalityType_item",
        ]);
    }
}