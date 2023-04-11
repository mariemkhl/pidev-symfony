<?php

namespace App\Form;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;


use App\Entity\ProdCollect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProdCollectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('Prod_id')
            ->add('Prod_nom')
            ->add('img')
            ->add('products', EntityType::class, [
                'label'=> 'products',
                'class' => Product::class,
                'choice_label' =>  function (Product $product) {
                    return sprintf('%s', $product->getId());
                },
                'placeholder' => 'Choose product',
                'attr' => ['class' => 'form-select'],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProdCollect::class,
        ]);
    }
}
