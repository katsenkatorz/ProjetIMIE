<?php
namespace AdminBundle\Form;

use AdminBundle\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("name", TextType::class, ["label" => "Nom du métier"])
            ->add("minSalary", IntegerType::class, ["label" => "Salaire minimum"])
            ->add("maxSalary", IntegerType::class, ["label" => "Salaire maximum"])
            ->add("description", CKEditorType::class, ["label" => "Description du poste"])
            ->add('image', FileType::class, ['required' => false])
            ->add("save", SubmitType::class, ["label" => "Créer un job"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => Job::class,
            "csrf_protection" => true,
            "csrf_field_name" => '_token',
            "csrf_token_id" => "job_item",
        ]);
    }
}