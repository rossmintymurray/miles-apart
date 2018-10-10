<?php

namespace MilesApart\StaffBundle\Form\Products\EditProduct;

use MilesApart\StaffBundle\Form\Products\EditProduct\EditProductFeatureType;
use MilesApart\StaffBundle\Form\Products\EditProduct\EditProductImageType;
use MilesApart\StaffBundle\Form\Products\EditProduct\EditProductPriceType;
use MilesApart\StaffBundle\Form\Products\EditProduct\EditAttributeValueType;
use MilesApart\StaffBundle\Form\Products\EditProduct\EditProductKeywordType;
use MilesApart\StaffBundle\Form\Products\EditProduct\EditProductSupplierType;
use MilesApart\StaffBundle\Form\Products\EditProduct\EditProductQuestionType;
use MilesApart\StaffBundle\Form\Products\EditProduct\EditProductReviewType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Doctrine\Common\Persistence\ObjectManager;

class EditProductType extends AbstractType
{   
    //Set up object manager for transformer
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_name', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Product name',
                'required'  => true,
            ));

            $builder
            ->add('product_marketing_name', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Product marketing name',
                'required'  => true,
            ));

            $builder
            ->add('short_name', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Product short name',
                'required'  => false,
            ));

            $builder
            ->add('print_subtitle', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Product print subtitle',
                'required'  => false,
            ));

            $builder
            ->add('product_marketing_sub_name', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Product marketing sub name',
                'required'  => true,
            ));

            $builder
            ->add('product_introduction', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Introduction',
                'required'  => true,
            ));
            
        $builder
            ->add('product_description', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Description',
                'required'  => false,
            ));
            
        $builder
            ->add('product_weight', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-2 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Weight (g)',
                'required'  => false,
            ));

        $builder
            ->add('product_width', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-2 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Width (mm)',
                'required'  => false,
            ));
            
        $builder
            ->add('product_height', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-2 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Height (mm)',
                'required'  => false,
            ));
            
        $builder
            ->add('product_depth', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-2 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Depth (mm)',
                'required'  => false,
            ))

            ->add('product_supplier_code', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-3 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Product supplier code',
                'required'  => false,
            ));

            $builder
            ->add('product_outer_barcode', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Outer barcode',
                'required'  => false,
            ));
            
        $builder
            ->add('product_inner_barcode', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Inner barcode',
                'required'  => false,
            ))

            ->add('product_barcode', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-3 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Barcode',
                'required'  => false,
            ))


            ->add('product_price', 'collection', array(
                
                'type'          => new EditProductPriceType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'price__name__',
                'options'       => array(
                     'attr'      => array('class' => 'hidde'),
                     'label' => false
                     ),
                'label'         => false,

                
                
            ))
           //Add the product 
            ->add('product_supplier', 'collection', array(
                
                'type'          => new EditProductSupplierType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'product_supplier__name__',
                'options'       => array(
                    'label'         => false,
                ),
               'label' => false,
                
            ))

            ;

            //Set up product feature form
        $builder
            ->add('product_feature', 'collection', array(
                
                'type'          => new EditProductFeatureType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'feature__name__',
                'options'       => array(
                    'label'         => false,
                ),
                'label' => false,
            ));

         //Set up product image form
        $builder
            ->add('product_image', 'collection', array(
                
                'type'          => new EditProductImageType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'image__name__',
                'options'       => array(
                    'label'         => false,
                ),
                'label' => false,
                
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
                
                'type'          => new EditAttributeValueType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'attribute__name__',
                'options'       => array(
                    'label'         => false,
                ),
                'label' => false,
                
            ));
            
        //Set up category form
        $builder
            ->add('keyword', 'collection', array(
                
                'type'          => new EditProductKeywordType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'keyword__name__',
                'options'       => array(
                    'label'         => false,
                ),
                'label' => false,
                
                
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
            ->add('is_product_discontinued', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Is discontinued?',
                'required'  => false,
                
            ));
        
        $builder
            ->add('product_outer_quantity', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Outer quantity',
                'required'  => false,
            ));
            
        $builder
            ->add('product_inner_quantity', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Inner quantity',
                'required'  => false,
            ));

            $builder
            ->add('product_pack_quantity', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Pack quantity',
                'required'  => false,
            ));

             $builder
            ->add('product_meta_description', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control tall_textarea'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Meta description',
                'required'  => false,
            ));

            $builder
            ->add('vat_rate_type', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-4 form-control'),
            'class' => 'MilesApartAdminBundle:VATRateType',
                    'property' => 'vat_rate_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('vat_rate_type')
                                  ->orderBy('vat_rate_type.vat_rate_type_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'VAT rate type',
            'empty_value' => 'Select one...',
            ));

        $builder
            ->add('brand', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-4 form-control'),
            'class' => 'MilesApartAdminBundle:Brand',
                    'property' => 'brand_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('brand')
                                  ->orderBy('brand.brand_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Brand',
            'empty_value' => 'Select one...',
            ));

            //Set up category form
        $builder
            ->add('product_question', 'collection', array(
                
                'type'          => new EditProductQuestionType($this->objectManager),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'product__question__',
                'options'       => array(
                    'label'         => false,
                ),
                'label' => false,
                
            ));

        //Set up category form
        $builder
            ->add('product_review', 'collection', array(
                
                'type'          => new EditProductReviewType($this->objectManager),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'product__review__',
                'options'       => array(
                    'label'         => false,
                ),
                'label' => false,
                
            ));

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Product',
            'csrf_protection' => false,
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