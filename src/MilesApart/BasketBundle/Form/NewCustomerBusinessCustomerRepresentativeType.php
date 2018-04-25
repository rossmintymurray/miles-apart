<?php

namespace MilesApart\BasketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class NewCustomerBusinessCustomerRepresentativeType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            $builder
            ->add('business_customer_representative_email_address', null, array(
                'attr' => array(
                    'type'=> 'email',
                    'pattern' => 'email'),
                'label_attr'=> array('class'=>'inline right responsive_form_label'),
                'label'=>'Email Address',
                'required'  => true,
            ));

        $builder
            ->add('business_customer_representative_first_name', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>'inline right responsive_form_label'),
                'label'=>'First Name',
                'required'  => true,
            ));

        $builder
            ->add('business_customer_representative_surname', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>'inline right responsive_form_label'),
                'label'=>'Surname',
                'required'  => true,
            ));

       
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_basketbundle_businesscustomer';
    }
}