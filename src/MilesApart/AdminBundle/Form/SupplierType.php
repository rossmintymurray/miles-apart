<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SupplierType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('supplier_name',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Name',
            ));

        $builder->add('supplier_account_number',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Account number',
            ));

        $builder->add('supplier_minimum_order_value',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Min order value',
            ));

         $builder->add('supplier_type', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'class' => 'MilesApartAdminBundle:SupplierType',
                    'property' => 'supplier_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('supplier_type')
                                  ->orderBy('supplier_type.supplier_type_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Type',
            ));

         $builder->add('supplier_minimum_order_format', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'class' => 'MilesApartAdminBundle:SupplierMinimumOrderFormat',
                    'property' => 'supplier_minimum_order_format_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('supplier_minimum_order_format_name')
                                  ->orderBy('supplier_minimum_order_format_name.supplier_minimum_order_format_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Minimum order format',
            ));


        $builder->add('supplier_address_1',null, array(
            'attr' => array(
                'class'=> 'form-control col-md-3'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Address 1',
            ));

        $builder->add('supplier_address_2',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Address 2',
            'required'  => false,
            ));

        $builder->add('supplier_town',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Town',
            ));

        $builder->add('supplier_county',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'County',
            ));

        $builder->add('supplier_postcode',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Postcode',
            ));

        $builder->add('supplier_country',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Country',
            ));

        $builder->add('supplier_phone',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Phone',
            ));

        $builder->add('supplier_fax',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Fax',
            'required'  => false,
            ));

        $builder->add('supplier_ordering_email',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Ordering email',
            'required'  => false,
            ));

        $builder->add('supplier_info_email',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Info email',
            'required'  => false,
            ));

        $builder->add('supplier_website',null, array(
            'attr' => array(
                'class'=> 'col-md-3 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Website',
            'required'  => false,
            ));

         //Set up supplier rep form
        $builder
            ->add('supplier_representative', 'collection', array(
                
                'type'         => new SupplierRepresentativeType(),
                'allow_add'    => true,
                'allow_delete'    => true,
                'prototype' => true,
                // Post update
                'by_reference' => false,
                'error_bubbling' => true,
                'label'         => false,
                
                
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Supplier'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_supplier';
    }
}
