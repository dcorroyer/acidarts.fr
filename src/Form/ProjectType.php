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
            ->add('title', TextType::class, [
                'attr'  => [
                    'class'       => 'title',
                    'placeholder' => 'TITLE *'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'attr'    => [
                    'class' => 'choices'
                ],
                'choices' => [
                    'Type of 3D project' => [
                        '-- Select the type --'     => null,
                        '3d realtime rendering'     => '3d realtime rendering',
                        '3d realtime application'   => '3d realtime application',
                        '3d mobile rendering'       => '3d mobile rendering',
                        '3d video game'             => '3d video game',
                        '3d architecture rendering' => '3d architecture rendering',
                    ]
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr'  => [
                    'class'       => 'description',
                    'placeholder' => 'DESCRIPTION *'
                ],
                'required' => false
            ])
            ->add('thumbFile', FileType::class, [
                'attr'     => [
                    'class' => 'thumbfile'
                ],
                'label'    => 'Thumbnail',
                'required' => false
            ])
            ->add('imageFiles', FileType::class, [
                'attr'     => [
                    'class' => 'imagefiles'
                ],
                'label'    => 'Images',
                'multiple' => true,
                'required' => false
            ])
            ->add('videos', CollectionType::class, [
                'allow_add'     => true,
                'allow_delete'  => true,
                'attr'          => [
                    'class' => 'videos'
                ],
                'by_reference'  => false,
                'entry_options' => ['label' => false],
                'entry_type'    => VideoType::class,
                'label'         => false
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
