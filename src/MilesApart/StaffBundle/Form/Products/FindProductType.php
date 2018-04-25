<?php

namespace MilesApart\StaffBundle\Form\Products;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use MilesApart\StaffBundle\Form\DataTransformer\ProductToNameTransformer;

class FindProductType extends AbstractType
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
                    'class'=> 'col-md-3 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Barcode',
                'required'  => false,
            ));

            $builder
            ->add(
                    'product_name', 'text', array(
                        'attr' => array('class'=> 'col-md-3 col-xs-12 form-control'),
                        'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                        'label'=>'Name',
                        'required'  => false,
                    
                ));

            $builder
            ->add('product_supplier_code', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-3 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Supplier code',
                'required'  => false,
            ));

            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\StaffBundle\Entity\Products\SearchProduct'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_findproduct';
    }
}
