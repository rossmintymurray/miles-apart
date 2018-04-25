<?php

namespace MilesApart\BasketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class NewCustomerBillingAddressType extends AbstractType
{
  
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       /* $builder
            ->add('customer_address_is_billing', "hidden", array(
                'attr' => array(
                    ),
                'label_attr'=> array('class'=>'inline right'),
                'label'=>'Billing address is same as delivery address?',
                'required'  => false,
                'data' => true,
            ));
*/
        $builder
            ->add('customer_address_line_1', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>'inline right responsive_form_label'),
                'label'=>'Address Line 1',
                'required'  => true,
            ));

        $builder
            ->add('customer_address_line_2', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>'inline right responsive_form_label'),
                'label'=>'Address Line 2',
                'required'  => false,
            ));

        $builder
            ->add('customer_address_town', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>'inline right responsive_form_label'),
                'label'=>'Town/City',
                'required'  => true,
            ));

        $builder
            ->add('customer_address_county', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>'inline right responsive_form_label'),
                'label'=>'County',
                'required'  => true,
            ));

         $builder
            ->add('customer_address_postcode', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha',
                    'class' => 'small-4'),
                'label_attr'=> array('class'=>'inline right responsive_form_label'),
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
        return 'milesapart_basketbundle_billingaddress';
    }
}