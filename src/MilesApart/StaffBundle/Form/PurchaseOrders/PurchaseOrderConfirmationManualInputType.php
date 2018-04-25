<?php

namespace MilesApart\StaffBundle\Form\PurchaseOrders;

use MilesApart\StaffBundle\Form\PurchaseOrders\PurchaseOrderConfirmationProductType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PurchaseOrderConfirmationManualInputType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('purchase_order_product', 'collection', array(
                
            'type'          => new PurchaseOrderConfirmationProductType(),
            'allow_add'     => true,
            'allow_delete'  => true,
            'prototype'     => true,
            'prototype_name' => 'purchase__order__product__name__',
            'options'       => array(
                 'attr'      => array('class' => 'hidde'),
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
            'data_class' => 'MilesApart\AdminBundle\Entity\PurchaseOrder'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_purchaseorderconfirmationmanualinput';
    }
}