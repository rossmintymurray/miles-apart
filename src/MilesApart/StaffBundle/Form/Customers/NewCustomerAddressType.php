<?php

namespace MilesApart\StaffBundle\Form\Customers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class NewCustomerAddressType extends AbstractType
{
  
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer_address_is_billing', "checkbox", array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Billing address is same as delivery address?',
                'required'  => false,
                'data' => true,
            ));

        $builder
            ->add('customer_address_line_1', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Address Line 1',
                'required'  => true,
            ));

        $builder
            ->add('customer_address_line_2', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Address Line 2',
                'required'  => false,
            ));

        $builder
            ->add('customer_address_town', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Town/City',
                'required'  => true,
            ));

        $builder
            ->add('customer_address_county', null, array(
                'attr' => array(
                     'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'County',
                'required'  => true,
            ));

         $builder
            ->add('customer_address_postcode', null, array(
                'attr' => array(
                     'class'=> 'col-md-2 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Postcode',
                'required'  => true,
            ));
 
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\CustomerAddress'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_customeraddress';
    }
}