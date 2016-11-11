<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TodoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function(CategoryRepository $repository){
                return $repository->createAlphabeticalQueryBuilder();
                },
                'multiple' => true,
                'expanded' => true
            ])
            ->add('description')
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High'
                ]
            ])
            ->add('dueDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'js-datepiker'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Todo'
        ]);
    }

    public function getName()
    {
        return 'app_bundle_todo_form';
    }
}
