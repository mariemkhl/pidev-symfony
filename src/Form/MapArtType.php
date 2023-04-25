<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Entity\MapArt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
class MapArtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomplace')
            ->add('description')
            ->add('lien')
            ->add('imageFile', VichFileType::class)
            ->add('nblikes',HiddenType::class)
            ->add('latitude',HiddenType::class)
            ->add('longitude',HiddenType::class)
            ->add('categorie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MapArt::class,
        ]);
    
    }
}
