<?php

namespace MilesApart\StaffBundle\Form\PurchaseOrders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

use MilesApart\StaffBundle\Form\DataTransformer\ProductToNameTransformer;

class PurchaseOrderConfirmationProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_supplier_code', 'text', array(
                'attr' => array(
                    'class'=> 'col-xs-12 form-control purchase_order_confirmation_product_supplier_code'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Supplier code',
                'required'  => true,
            ));

        $builder
            ->add('product_name', 'text', array(
                'attr' => array('class'=> 'col-xs-12 form-control add_product_to_list_product_name'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Search product',
                'required'  => false,
            ));

        $builder
            ->add('product_barcode', 'text', array(
                'attr' => array(
                    'class'=> 'col-xs-12 form-control add_product_to_list_product_barcode'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Barcode',
                'required'  => false,
            ));

        $builder
            ->add('product_outer_quantity', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Outer quantity',
                'required'  => false,
            ));
            
        $builder
            ->add('product_inner_quantity', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Inner quantity',
                'required'  => false,
            ));

        $builder
            ->add('purchase_order_product_quantity', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'PO Quantity',
                'required'  => false,
            ));

        $builder ->add('product_barcode', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-12 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Barcode',
                'required'  => false,
            ));

        $builder ->add('product_cost', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-12 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Cost',
                'required'  => false,
                'mapped' => false
            ));

        $builder ->add('total_cost', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-12 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>' TotalCost',
                'required'  => false,
                'mapped' => false
            ));


        
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_addproducttopurchaseorder';
    }
}
