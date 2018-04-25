<?php

namespace MilesApart\StaffBundle\Form\Products;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewProductGroupType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder
            ->add(
                    'product_group_name', 'text', array(
                        'attr' => array('class'=> 'col-md-7 col-xs-12 form-control'),
                        'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                        'label'=>'Group Name',
                        'required'  => true,
                    
                ));

        $builder
            ->add('product_group_default_price', 'money', array(
                'currency' => 'GBP',
                'attr' => array(
                    'class'=> 'col-md-7 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Default price',
                'required'  => false,
            )) ;

        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductGroup'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_newproductgroup';
    }
}