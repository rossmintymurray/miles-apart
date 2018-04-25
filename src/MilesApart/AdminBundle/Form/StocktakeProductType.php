<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class StocktakeProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stocktake_product_qty', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Stocktake product qty',
                'required'  => true,
            ))

            ->add('stocktake', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Stocktake',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Stocktake',
                'property' => 'stocktake_date', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('stocktake')
                                  ->orderBy('stocktake.stocktake_date', 'ASC');
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

            ->add('stock_location', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Counted stock location',
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
            'data_class' => 'MilesApart\AdminBundle\Entity\StocktakeProduct'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_stocktakeproduct';
    }
}
