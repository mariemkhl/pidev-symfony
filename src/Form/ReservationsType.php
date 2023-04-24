<?php

namespace App\Form;

use App\Entity\Reservations;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Events;
use App\Entity\Utilisateur;


class ReservationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('idEvent' , EntityType::class,[
            'class' => Events::class , 
            'choice_label'=> 'nameev',
            'expanded'=> false , 
            'multiple'=>false , 
            'placeholder'=>'choose an option '
        ])
        ->add('idUser', EntityType::class,[
            'class'=>Utilisateur::class,
            'choice_label'=>'username',
            'expanded'=>false,
            'multiple'=> false , 
            'placeholder'=>'choose an option '
        ])
            ->add('name')
            ->add('datere')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
