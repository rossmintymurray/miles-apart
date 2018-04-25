<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Name',
                'required'  => true,
            ));
            
        $builder
            ->add('product_introduction', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Introduction',
                'required'  => true,
            ));
            
        $builder
            ->add('product_description', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Description',
                'required'  => false,
            ));
            
        $builder
            ->add('product_weight', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Weight (g)',
                'required'  => false,
            ));

        $builder
            ->add('product_width', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Width (mm)',
                'required'  => false,
            ));
            
        $builder
            ->add('product_height', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Height (mm)',
                'required'  => false,
            ));
            
        $builder
            ->add('product_depth', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Depth (mm)',
                'required'  => false,
            ));

        $builder
            ->add('product_supplier_code', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Supplier code',
                'required'  => false,
            ));
            
        $builder
            ->add('product_outer_barcode', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Outer barcode',
                'required'  => false,
            ));
            
        $builder
            ->add('product_inner_barcode', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Inner barcode',
                'required'  => false,
            ));

            $builder
            ->add('product_barcode', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Barcode',
                'required'  => false,
            ));

        $builder
            ->add('product_outer_quantity', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Outer quantity',
                'required'  => false,
            ));
            
        $builder
            ->add('product_inner_quantity', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Inner quantity',
                'required'  => false,
            ));

            $builder
            ->add('product_pack_quantity', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Pack quantity',
                'required'  => false,
            ));
        
        $builder
            ->add('vat_rate_type', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'class' => 'MilesApartAdminBundle:VATRateType',
                    'property' => 'vat_rate_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('vat_rate_type')
                                  ->orderBy('vat_rate_type.vat_rate_type_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'VAT rate type',
            ));

        $builder
            ->add('brand', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'class' => 'MilesApartAdminBundle:Brand',
                    'property' => 'brand_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('brand')
                                  ->orderBy('brand.brand_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Brand',
            'empty_value' => 'Select brand, if applicable',
            ));

        //Set up product feature form
        $builder
            ->add('product_feature', 'collection', array(
                
                'type'          => new ProductFeatureType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'feature__name__',
                'options'       => array(),
                
            ));

         //Set up product image form
        $builder
            ->add('product_image', 'collection', array(
                
                'type'          => new ProductImageType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'image__name__',
                'options'       => array(),
                
                
            ));

        //Set up category form
        $builder
            ->add('category', 'collection', array(
                
                'type'          => 'entity',
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'category__name__',
                'options'       => array(
                    'attr' => array(
                        'class'=> 'col-md-7 form-control'),
                    'class' => 'MilesApartAdminBundle:Category',
                    'property' => 'category_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('category')
                                  ->orderBy('category.category_name', 'ASC');
                    },
                    'label_attr'=> array('class'=>'col-md-4 control-label'),
                    'label'=>'Category',
                ),
                
                
            ));

        /*$builder
            ->add('category', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'class' => 'MilesApartAdminBundle:Category',
                    'property' => 'category_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('category')
                                  ->orderBy('category.category_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Category',
            ));*/

        //Set up category form
        $builder
            ->add('season', 'collection', array(
                
                'type'          => 'entity',
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'season__name__',
                'options'       => array(
                    'attr' => array(
                        'class'=> 'col-md-7 form-control'),
                    'class' => 'MilesApartAdminBundle:Season',
                    'property' => 'season_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('season')
                                  ->orderBy('season.season_name', 'ASC');
                    },
                    'label_attr'=> array('class'=>'col-md-4 control-label'),
                    'label'=>'Season',
                ),
                
                
            ));

        //Set up category form
        $builder
            ->add('attribute_value', 'collection', array(
                
                'type'          => new AttributeValueType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'attribute__name__',
                'options'       => array(),
                
                
            ));
            
        //Set up category form
        $builder
            ->add('keyword', 'collection', array(
                
                'type'          => new KeywordType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'keyword__name__',
                'options'       => array(),
                
                
            ));

        $builder
            ->add('is_product_online', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Is online?',
                'required'  => true,
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_product';
    }
}
