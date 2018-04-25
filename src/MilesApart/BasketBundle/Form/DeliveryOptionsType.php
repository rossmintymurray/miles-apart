<?php

namespace MilesApart\BasketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class DeliveryOptionsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('customer_address_line_1', null, array(
                'attr' => array(
                    'type'=> 'text'),
                'label_attr'=> array('class'=>''),
                'label'=>'Address Line 1',
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
        return 'milesapart_basketbundle_delivery-options';
    }
}
