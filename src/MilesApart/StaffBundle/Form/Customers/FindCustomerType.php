<?php

namespace MilesApart\StaffBundle\Form\Customers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FindCustomerType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer_name', null, array(
                'mapped' => false,
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control customer_search_field'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Customer name',
                'required'  => false,
            ))
            ->add('customer_email', null, array(
                'mapped' => false,
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control customer_search_field'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Customer email',
                'required'  => false,
            ))
            ->add('business_name', null, array(
                'mapped' => false,
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control customer_search_field'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Business name',
                'required'  => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => "MilesApart\AdminBundle\Entity\Customer"
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundlefindcustomer';
    }
}
