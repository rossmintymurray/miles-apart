<?php

namespace MilesApart\StaffBundle\Form\Products\EditProduct;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EditProductPriceType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('product_price_valid_from','date', array(
            'attr' => array(
                'label'=>'Valid from',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -5),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Valid from',
            'required'  => false,
            ));

        $builder->add('product_price_valid_until','date', array(
            'attr' => array(
                'label'=>'Valid until',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -5),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Valid until',
            'required'  => false,
            ));

        $builder
            ->add('product_price_value', 'number', array(
                'precision'=>2,
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Price',
                'required'  => true,
            ));

        $builder
            ->add('product_price_is_special', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Is special',
                'required'  => false,
            ));

        

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductPrice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_productprice';
    }
}
