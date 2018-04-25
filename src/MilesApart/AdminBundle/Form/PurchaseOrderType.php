<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PurchaseOrderType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplier_purchase_order_code', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Purchase order code',
                'required'  => true,
            ))

            ->add('purchase_order_date_completed', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Purchase order date completed',
                'required'  => true,
            ))

            ->add('supplier', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Supplier',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Supplier',
                'property' => 'supplier_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('supplier')
                                  ->orderBy('supplier.supplier_name', 'ASC');
                    },
            ))

            ->add('purchase_order_state', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Purchase order state',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:PurchaseOrderState',
                'property' => 'purchase_order_state', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('purchase_order_state')
                                  ->orderBy('purchase_order_state.purchase_order_state', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\PurchaseOrder'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_purchaseorder';
    }
}
