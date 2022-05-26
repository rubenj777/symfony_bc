<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\SubCategory;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('isActive')
            ->add('brand')
            ->add('quantity')
            ->add('description')
            ->add('category', EntityType::class, ['class'=>Category::class, 'choice_label'=>'name'])
            ->add('subcategory', EntityType::class, ['class'=>SubCategory::class,
                'query_builder'=>function(EntityRepository $er) {
                return $er->createQueryBuilder('sc')
                    ->addSelect('sc')
                    ->join('sc.category', 'c')
                    ->orderBy('c.name', 'ASC')
                    ->addOrderBy('sc.name', 'ASC')
                    ->groupBy('sc.category');
                },
                'choice_label'=> function($sub) {
                return $sub->getCategory()->getName()." - " . $sub->getName();
                },
                ])
            ->add('imageFile', VichImageType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
