<?php

namespace MilesApart\StaffBundle\Form\GoodsIn;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class StoreDeliveryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('supplier_delivery_product', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'class' => 'MilesApartAdminBundle:SupplierDeliveryProduct',
                    'property' => 'id', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('supplier_delivery_product')
                                  ->orderBy('supplier_delivery_product.id', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Delivered product',
            ));

            $builder
            ->add('stock_location_shelf', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'class' => 'MilesApartAdminBundle:StockLocationShelf',
                    'property' => 'stock_location_shelf_code', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('stock_location_shelf')
                                  ->orderBy('stock_location_shelf.stock_location_shelf_code', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Stock shelf',
            ));

             $builder
            ->add('stock_location_shelf_product_sent_qty', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Stored qty',
                'required'  => true,
            ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\StockLocationShelfProductSent'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_storedelivery';
    }
}