<?php

namespace App\Form;

use App\Entity\Events;
use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;




class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameev' , TextType::class , [
                'label' => 'Name',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a name',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z\s]*$/',
                        'message' => 'The name field can only contain letters and spaces',
                    ]),
                ],
            ])

            ->add('dateEvent' ) 


            ->add('location' , TextType::class, [
                'label' => 'Location',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter Information ! ',
                    ]),
                ],
            ])

            ->add('idUser', EntityType::class,[
                'class'=>Utilisateur::class,
                'choice_label'=>'username',
                'expanded'=>false,
                'multiple'=> false , 
                'placeholder'=>'choose an option '
            ])
            ->add('categorie' , TextType::class, [
                'label' => 'Category',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter Information ! ',
                    ]),
                ],
            ])

            ->add('nbplacetotal')

            ->add('img' ,FileType:: class , [
                'label' => 'Upload image',
                'mapped' => false,
                'required' => false,
            ])

            ->add('description' , TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter Information ! ',
                    ]),
                ],
            ])
        ;
    }

  /*  public function validateEventdate($value, ExecutionContextInterface $context)
    {
        $today = new \DateTime();
        if ($value < $today) {
            $context->buildViolation('The Event date must not be before today.')
                ->atPath('Eventdate')
                ->addViolation();
        }
    }*/

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }
}
