<?php
namespace AdminBundle\Form;

use AdminBundle\Entity\JobTemperament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobTemperamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("value", RangeType::class, [
            "label" => "Valeur",
            "attr" => [
                "min" => -100,
                "max" => 100,
                "value" => 0
            ]
        ])
            ->add("job", HiddenType::class)
            ->add("temperament", HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => JobTemperament::class,
            "csrf_protection" => true,
            "csrf_field_name" => '_token',
            "csrf_token_id" => "jobTemperament_item",
        ]);
    }
}