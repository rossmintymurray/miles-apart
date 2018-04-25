<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CustomerOptInType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('opt_in', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Opt in true',
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
            
            ->add('customer_opt_in_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Opt in type',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CustomerOptInType',
                'property' => 'customer_opt_in_type_text', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer_opt_in_type')
                                  ->orderBy('customer_opt_in_type.customer_opt_in_type_text', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\CustomerOptIn'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_customeroptin';
    }
}
