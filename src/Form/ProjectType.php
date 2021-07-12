<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Type of 3D project' => [
                        '3d realtime rendering'     => '3d realtime rendering',
                        '3d realtime application'   => '3d realtime application',
                        '3d mobile rendering'       => '3d mobile rendering',
                        '3d video game'             => '3d video game',
                        '3d architecture rendering' => '3d architecture rendering',
                    ],
                ],
            ])
            ->add('description', TextareaType::class)
            ->add('thumbFile', FileType::class, [
                'required' => false
            ])
            ->add('imageFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])
            ->add('videos', CollectionType::class, [
                'entry_type'    => VideoType::class,
                'entry_options' => ['label' => false],
                'allow_add'     => true,
                'allow_delete'  => true,
                'by_reference'  => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
