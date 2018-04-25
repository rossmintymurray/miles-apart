<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CustomerOrderProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer_order_product_quantity', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Order quantity',
                'required'  => true,
            ))

            ->add('customer_order', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Customer Order',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CustomerOrder',
                'property' => 'id', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer_order')
                                  ->orderBy('customer_order.id', 'ASC');
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

            ->add('customer_order_state', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Order State',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CustomerOrderState',
                'property' => 'customer_order_state', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer_order_state')
                                  ->orderBy('customer_order_state.customer_order_state', 'ASC');
                    },
            ))

            
        ;
    }
    
    /* 
     * Generic form types for reference:
     * 
     *Date:
     *$builder->add('employee_date_of_birth','date', array(
            'attr' => array(
                'label'=>'Date of birth',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -65),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Date of birth',
            'required'  => false,
            ))

    * 
     *Entity:
     $builder
            ->add('competitor_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Type',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CompetitorType',
                'property' => 'competitor_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('competitor_type')
                                  ->orderBy('competitor_type.competitor_type_name', 'ASC');
                    },
            ))


            

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\CustomerOrderProduct'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_customerorderproduct';
    }
}
