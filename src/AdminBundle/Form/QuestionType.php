<?php
namespace AdminBundle\Form;

use AdminBundle\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("label", TextType::class, ["label" => "Intitulé de la question"])
            ->add("temperament", HiddenType::class)
            ->add("save", SubmitType::class, ["label" => "Créer une question"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Question::class,
            "csrf_protection" => true,
            "csrf_field_name" => '_token',
            "csrf_token_id" => "question_item",
        ]);
    }
}