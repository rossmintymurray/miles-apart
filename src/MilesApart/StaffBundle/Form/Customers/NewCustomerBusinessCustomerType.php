<?php

namespace MilesApart\StaffBundle\Form\Customers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class NewCustomerBusinessCustomerType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('business_customer_name', null, array(
                'attr' => array(
                     'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Business Name',
                'required'  => false,
            ));

            $builder->add('business_customer_representative',new NewCustomerBusinessCustomerRepresentativeType(),array(

                            'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative',
                            'required' =>false,
                            'mapped' => false,

                    )
                );

       
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_businesscustomer';
    }
}