<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CustomerOrderType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('session_id', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Session ID',
                'required'  => true,
            ))

            ->add('customer', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Customer',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Customer',
                'property' => 'id', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer')
                                  ->orderBy('customer.id', 'ASC');
                    },
            ))

            ->add('delivery_address', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Delivery address',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CustomerAddress',
                'property' => 'customer_address_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer_address')
                                  ->orderBy('customer_address.customer_address_name', 'ASC');
                    },
            ))
            
            ->add('billing_address', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Billing address',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CustomerAddress',
                'property' => 'customer_address_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer_address')
                                  ->orderBy('customer_address.customer_address_name', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\CustomerOrder'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_customerorder';
    }
}
