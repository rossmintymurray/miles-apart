<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SupplierInvoicePurchaseOrderType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplier_invoice', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Supplier invoice',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:SupplierInvoice',
                'property' => 'supplier_invoice_code', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('supplier_invoice')
                                  ->orderBy('supplier_invoice.supplier_invoice_code', 'ASC');
                    },
            ))

            ->add('purchase_order', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Purchase order',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:PurchaseOrder',
                'property' => 'purchase_order_date_created', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('purchase_order')
                                  ->orderBy('purchase_order.purchase_order_date_created', 'ASC');
                    },
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\SupplierInvoicePurchaseOrder'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_supplierinvoicepurchaseorder';
    }
}
