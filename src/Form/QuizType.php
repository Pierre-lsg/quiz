<?php

namespace App\Form;

use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('description')
            ->add('picture', FileType::class, [
                'label' => 'Picture (JPG file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048K',
                        'mimeTypes' => [
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPG picture',
                    ])
                ],
            ])
            ->add('creator')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
