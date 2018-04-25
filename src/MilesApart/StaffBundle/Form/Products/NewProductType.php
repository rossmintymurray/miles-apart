<?php

namespace MilesApart\StaffBundle\Form\Products;

use MilesApart\AdminBundle\Form\ProductFeatureType;
use MilesApart\AdminBundle\Form\ProductImageType;
use MilesApart\AdminBundle\Form\AttributeValueType;
use MilesApart\AdminBundle\Form\KeywordType;
use MilesApart\StaffBundle\Form\Products\ProductSupplierType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class NewProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_name', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-7 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Product name',
                'required'  => true,
            ));

        $builder
            ->add('product_marketing_name', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-7 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Marketing name',
                'required'  => false,
            ));

        $builder
            ->add('product_marketing_sub_name', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-7 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Marketing sub name',
                'required'  => false,
            ));

        $builder
            ->add('product_meta_description', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-7 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Product meta description',
                'required'  => false,
            ));

        $builder
            ->add('short_name', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-7 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Short name',
                'required'  => false,
            ));

        $builder
            ->add('print_subtitle', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-7 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Print subtitle',
                'required'  => false,
            ));

            $builder
            ->add('product_introduction', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Introduction',
                'required'  => false,
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
            ))

            ->add('product_supplier_code', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-7 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Product code',
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
            ))

            ->add('product_barcode', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-7 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Barcode',
                'required'  => false,
            ))


            ->add('product_price', 'collection', array(
                
                'type'          => new ProductPriceType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'price__name__',
                'options'       => array(),
                'label'         => false,

                
                
            ))
           //Add the product 
            ->add('product_supplier', 'collection', array(
                
                'type'          => new ProductSupplierType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'product_supplier__name__',
                'options'       => array(),
                'label'         => false,

                
                
            ))

            ;

            //Set up product feature form
        $builder
            ->add('product_feature', 'collection', array(
                
                'type'          => new ProductFeatureType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'feature__name__',
                'options'       => array(),
                'label'         => false,
                
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
                'label'         => false,
                
                
            ));

        $builder
            ->add('category', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control',
                    'style' => 'height:400px',
                    ),
                'multiple' => true,   // Multiple selection allowed
                'expanded' => false,
                'group_by' => 'parent.parent.categoryName', 
                'property' => 'category_name', // Assuming that the entity has a "name" property
                'class'    => 'MilesApart\AdminBundle\Entity\Category',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.category_type = 3')
                        ->orderBy('c.category_name', 'ASC');
                },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
           
                
            ));

        $builder
            ->add('product_default_category', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-4 form-control'
                ),
            'group_by' => 'parent.parent.categoryName', 
            'class' => 'MilesApartAdminBundle:Category',
            'property' => 'category_name', 
            'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('c')
                            ->where('c.category_type = 3')
                          ->orderBy('c.category_name', 'ASC');
            },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Default category type',
            'empty_value' => 'Choose an option',
            'required'  => false,
            ));

        //Set up category form
        $builder
            ->add('season', 'collection', array(
                
                'type'          => 'entity',
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'season__name__',
                'label'         => false,
                'options'       => array(
                    'attr' => array(
                        'class'=> 'col-md-4 form-control'),
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
                'label'         => false,
                
                
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
                'label'         => false,
                
                
            ));

        $builder
            ->add('is_product_online', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Is online?',
                'required'  => false,
            ));

        $builder
            ->add('is_product_on_amazon', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Is on Amazon?',
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
                                  ->orderBy('vat_rate_type.id', 'ASC');
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
            'required'  => false,
            ));
        ;
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
        return 'milesapart_staffbundle_newproduct';
    }
}