<?php

namespace App\Form;

use App\Entity\Paiment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Paiment1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numCarte')
            ->add('nomCarte')
            ->add('dateEx')
            ->add('cvCode')
          //  ->add('prixTot')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiment::class,
        ]);
    }
}
