<?php

namespace MilesApart\StaffBundle\Form\Categories;

use MilesApart\StaffBundle\Form\Categories\BrandDescriptionParagraphType;
use MilesApart\StaffBundle\Form\Categories\BrandFeatureType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class BrandType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Brand name',
                'required'  => true,
            ));
        
        $builder
            ->add('brand_introduction', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Brand introduction',
                'required'  => false,
            ));

        $builder
            ->add('file', 'file', array(
                'attr' => array(
                    'class'=> 'col-md-7'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Brand image',
                'required'  => false,
            ));
        
        $builder
            ->add('brand_description_paragraph', 'collection', array(
                
                'type'          => new BrandDescriptionParagraphType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'brand__desc__para__name__',
                'options'       => array(
                     
                     'label' => false
                     ),
                'label'         => false,

                
                
            ));

            $builder
            ->add('brand_feature', 'collection', array(
                
                'type'          => new BrandFeatureType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'brand__feature__name__',
                'options'       => array(
                     
                     'label' => false
                     ),
                'label'         => false,

                
                
            ));


    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Brand'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_newbrand';
    }
}
