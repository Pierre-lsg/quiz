<?php

namespace App\Form;

use App\Entity\ResultComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lowerBound')
            ->add('upperBound')
            ->add('comment')
            ->add('reward')
            ->add('quiz')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResultComment::class,
        ]);
    }
}
