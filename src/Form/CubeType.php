<?php

namespace App\Form;

use App\Entity\Cube;
use App\Entity\CubeCollection;
use App\Entity\CubeGallery;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CubeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('imageFile', VichImageType::class, [
                'required' => false,
            ])
            ->add('cubeCollection', EntityType::class, [
                'class' => CubeCollection::class,
                'choice_label' => 'id',
                'disabled' => true,
            ])
            ->add('cubeGalleries', EntityType::class, [
                'class' => CubeGallery::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cube::class,
        ]);
    }
}
