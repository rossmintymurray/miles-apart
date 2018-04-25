<?php

namespace MilesApart\StaffBundle\Form\Pickpack;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;



class PickType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */  
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('id', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-3 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Order barcode',
                'required'  => false,
                'mapped'=>false,
            ));

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
        return 'milesapart_staffbundle_pick';
    }
}
