<?php

namespace MilesApart\BasketBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ExistingCustomerCheckoutType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('username', null, array(
                'attr' => array(
                    'type'=> 'email',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Email',
                'required'  => true,
            ));

        $builder
            ->add('password', 'password', array(
                'attr' => array(
                    'type'=> 'password'),
                'label_attr'=> array('class'=>''),
                'label'=>'Password',
                'required'  => true,
            ));
    }
    

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\FosUser'
        ));
    }
    

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_basketbundle_existingcustomercheckout';
    }
}
