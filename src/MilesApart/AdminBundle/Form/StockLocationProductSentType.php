<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class StockLocationProductSentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stock_location_product_sent_qty', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Product sent qty',
                'required'  => true,
            ))

            ->add('supplier_delivery_product', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Supplier delivery product',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:SupplierDeliveryProduct',
                'property' => 'id', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('supplier_delivery_product')
                                  ->orderBy('supplier_delivery_product.id', 'ASC');
                    },
            ))

            ->add('stock_location', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Stock location',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:StockLocation',
                'property' => 'stock_location_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('stock_location')
                                  ->orderBy('stock_location.stock_location_name', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\StockLocationProductSent'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_stocklocationproductsent';
    }
}
