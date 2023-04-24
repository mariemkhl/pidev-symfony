<?php

namespace App\Form;



use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Entity\ProdCollect;
use App\Form\ProdCollectType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;



use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('prix')
            // ->add('img')
            ->add('img', FileType::class, [
                'mapped' => false,
            ])
            // ->add('categ')
            ->add('user')
            ->add('url')
            ->add('date_ajout')
            // ->add('date_achat')
            // ->add('category')

            
            // ->add('PRODcol')


            ->add('category', EntityType::class, [
                'label'=> 'categories',
                'class' => Category::class,
                'choice_label' =>  function (Category $category) {
                    return sprintf('%s', $category->getNom());
                },
                'placeholder' => 'Choose category',
                'attr' => ['class' => 'form-select'],
            ])


        

        




            // ->add('category', EntityType::class, [
            //     'class' => Category::class,
            //     'choice_label' => 'nom', 
            //     'placeholder' => 'Select a category',
            // ])


            // ->add('PRODcol', EntityType::class, [
            //     'class' => ProdCollect::class,
            //     'choice_label' => 'nom', 
            //     'placeholder' => 'Select a category',
            // ]);

            // ->add('PRODcol', EntityType::class, [
            //     'label'=> 'prod_collects',
            //     'class' => ProdCollect::class,
            //     'choice_label' =>  function (ProdCollect $prodCollect) {
            //         return sprintf('%s', $prodCollect->getId());
            //     },
            //     'placeholder' => 'Choose collection',
            //     'attr' => ['class' => 'form-select'],
            // ])

           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
