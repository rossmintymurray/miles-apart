<?php

namespace MilesApart\StaffBundle\Form\Helpers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecordReturnType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_barcode', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-5 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-3 col-xs-12 control-label'),
                'label'=>'Barcode',
                'required'  => true,
            ));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_recordreturn';
    }
}
