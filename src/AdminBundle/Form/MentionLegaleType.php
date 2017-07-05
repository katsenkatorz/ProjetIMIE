<?php
namespace AdminBundle\Form;

use AdminBundle\Entity\Parameters;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MentionLegaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("value", CKEditorType::class, ["label" => "Intitulé de la question"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Parameters::class,
            "csrf_protection" => true,
            "csrf_field_name" => '_token',
            "csrf_token_id" => "question_item",
        ]);
    }
}