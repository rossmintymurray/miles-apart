<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProductCostType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_cost_valid_from','date', array(
            'attr' => array(
                'label'=>'Cost valid from',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -1),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Cost valid from',
            'required'  => true,
            ))

            ->add('product_cost_valid_until','date', array(
            'attr' => array(
                'label'=>'Cost valid until',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -1),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Cost valid until',
            'required'  => false,
            ))

            ->add('product_cost_value', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Cost value',
                'required'  => true,
            ))

            ->add('product_cost_is_special', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Cost is special?',
                'required'  => true,
            ))

            ->add('product_supplier', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Product supplier',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:ProductSupplier',
                'property' => 'id', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('product_supplier')
                                  ->orderBy('product_supplier.id', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductCost'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_productcost';
    }
}
