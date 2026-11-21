<?php

namespace App\Form;

use App\Entity\Cube;
use App\Entity\CubeGallery;
use App\Entity\Member;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\CubeRepository;

class CubeGalleryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $cubeGallery = $options['data'] ?? null;
        $member = $cubeGallery->getMember();
        
        $builder
            ->add('description')
            ->add('publiee')
            ->add('member', EntityType::class, [
                'class' => Member::class,
                'choice_label' => 'id',
                'disabled' => true,
            ])
            
            ->add('cubes', EntityType::class, [
                'class' => Cube::class,
                'by_reference' => false,
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (CubeRepository $er) use ($member) {
                return $er->createQueryBuilder('c')
                ->leftJoin('c.cubeCollection', 'cc')
                ->leftJoin('cc.member', 'm')
                ->andWhere('m.id = :memberId')
                ->setParameter('memberId', $member->getId());
                },
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
