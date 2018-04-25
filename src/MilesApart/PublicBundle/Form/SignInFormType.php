<?php

namespace MilesApart\PublicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class SignInFormType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('email', 'email', array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'email'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Email',
                'required'  => true,
            ));

        $builder
            ->add('password', 'password', array(
                'attr' => array(
                    'type'=> 'password'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Password',
                'required'  => true,
            ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\PersonalCustomer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_publicbundle_sign_in_form';
    }
}
