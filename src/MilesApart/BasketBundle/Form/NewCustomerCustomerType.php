<?php

namespace MilesApart\BasketBundle\Form;

use MilesApart\BasketBundle\Form\NewCustomerPersonalCustomerType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class NewCustomerCustomerType extends AbstractType
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
        return 'milesapart_basketbundle_customer';
    }
}