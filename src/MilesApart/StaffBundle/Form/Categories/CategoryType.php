<?php

namespace MilesApart\StaffBundle\Form\Categories;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CategoryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Category name',
                'required'  => true,
            ));
        $builder
            ->add('file', 'file', array(
                'attr' => array(
                    'class'=> 'col-md-4'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Image',
                'required'  => false,
            ));
        $builder
            ->add('category_image_path', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Category image path',
                'required'  => false,
            ));

        $builder
            ->add('category_description', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Category description',
                'required'  => false,
            ));

        $builder
            ->add('category_navigation_display', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Navigation display',
                'required'  => false,
            ));

        $builder
            ->add('category_products_display', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Products display',
                'required'  => false,
            ));

        $builder
            ->add('category_description_display', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Display description?',
                'required'  => false,
            ));
      
        $builder
                ->add('category_type', 'entity', array(
                    'attr' => array(
                        'class'=> 'col-md-4 form-control'),
                    'label_attr'=> array('class'=>'col-md-4 control-label'),
                    'label'=>'Category type',
                    'required'  => true,
                    'class' => 'MilesApartAdminBundle:CategoryType',
                    'property' => 'category_type_name',
                        'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('category_type')
                                      ->orderBy('category_type.category_type_name', 'ASC');
                        },
                ));

        $builder
            ->add('parent', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Belongs to',
                'empty_value' => 'If sub or specific, chose an owner',
                'required'  => false,
                'class' => 'MilesApartAdminBundle:Category',
                'property' => 'category_name', 
                    'query_builder' => function(EntityRepository $er) {
                            return $er->createQueryBuilder('category')
                                      ->orderBy('category.category_name', 'ASC');
                        },

                               
            ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_newcategory';
    }
}
