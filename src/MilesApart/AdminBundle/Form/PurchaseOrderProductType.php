<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PurchaseOrderProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('purchase_order_product_quantity', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Purchase order qty',
                'required'  => true,
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

            ->add('product', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Product',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Product',
                'property' => 'product_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('product')
                                  ->orderBy('product.product_name', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\PurchaseOrderProduct'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_purchaseorderproduct';
    }
}
