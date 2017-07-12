<?php
namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("label", TextType::class, [
            "label" => "Intitulé de la réponse"
            ])
            ->add("value", RangeType::class, [
                "attr" => [
                    "min" => -100,
                    "max" => 100,
                    "value" => 0
                ]
            ])
            ->add("image", FileType::class, [
                "label" => "Image",
                "required" => false,
            ])
            ->add("question", HiddenType::class)
            ->add("save", SubmitType::class, ["label" => "Créer une réponse"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "csrf_protection" => true,
            "csrf_field_name" => '_token',
            "csrf_token_id" => "response_item",
        ]);
    }
}