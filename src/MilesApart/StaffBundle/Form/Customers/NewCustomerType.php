<?php

namespace MilesApart\StaffBundle\Form\Customers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class NewCustomerType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

         $builder->add('personal_customer',new NewCustomerPersonalCustomerType(),array(

                            'data_class' => 'MilesApart\AdminBundle\Entity\PersonalCustomer',
                            'required' =>false,

                    )
                );

         $builder->add('business_customer',new NewCustomerBusinessCustomerType(),array(

                            'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomer',
                            'required' =>false,

                    )
                );

         //Add the delivery address 
         $builder
            ->add('customer_address', 'collection', array(
                
                'type'          => new NewCustomerAddressType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'customer__address__name__',
                'options'       => array(),
                'label'         => false,

                
                
            ));

      
           

        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Customer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_customer';
    }
}