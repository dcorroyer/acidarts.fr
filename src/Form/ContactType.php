<?php
namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr'  => [
                    'class'       => 'surname',
                    'placeholder' => 'SURNAME / NOM *'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr'  => [
                    'class'       => 'name',
                    'placeholder' => 'NAME / PRENOM *'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr'  => [
                    'class'       => 'email',
                    'placeholder' => 'EMAIL *'
                ]
            ])
            ->add('subject', TextType::class, [
                'label' => false,
                'attr'  => [
                    'class'       => 'subject',
                    'placeholder' => 'SUBJECT / SUJET *'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr'  => [
                    'class'       => 'message',
                    'placeholder' => 'YOUR MESSAGE / VOTRE MESSAGE *'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => Contact::class,
            'translation_domain' => 'forms'
        ]);
    }
}
