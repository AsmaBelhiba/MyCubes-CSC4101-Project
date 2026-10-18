<?php

namespace App\Form;

use App\Entity\Cube;
use App\Entity\CubeGallery;
use App\Entity\Member;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CubeGalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('publiee')
            ->add('member', EntityType::class, [
                'class' => Member::class,
                'choice_label' => 'id',
            ])
            ->add('cubes', EntityType::class, [
                'class' => Cube::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CubeGallery::class,
        ]);
    }
}
