<?php

namespace MilesApart\SellerBundle\Form;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use MilesApart\StaffBundle\Form\Products\NewProductType;

class NewProductModalType extends NewProductType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
            ->add('product_barcode', 'hidden', array(
                'attr' => array(
                    'class'=> 'col-md-3 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Barcode',
                'required'  => true,
            ));
        ;
    }
    

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_sellerbundle_amazonproductmodal';
    }
}
