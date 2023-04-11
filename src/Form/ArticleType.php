<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;





class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
        ->add('titreArticle', TextType::class, [
            'label' => 'Titre',
            'constraints' => [
                new Regex([
                    'pattern' => '/^[a-zA-Z0-9 ]*$/',
                    'message' => 'Le titre ne doit pas contenir de symboles.'
                ])
            ]
        ])
        
        
            ->add('dateArticle')
            ->add('contentArticle', TextareaType::class, [
                'label' => 'Contenu de l\'article',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le contenu ne peut pas être vide',
                    ]),
                ],
            ])
           // ->add('nbrlikesArticle')
            ->add('imageArticle', FileType::class, [
                'label' => 'Upload Image',
                'mapped' => false,
                'required' => false,
            ])
            ->add('categoryArticle')
            ->add('iduser')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
