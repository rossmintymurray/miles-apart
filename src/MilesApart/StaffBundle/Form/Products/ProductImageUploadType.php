<?php

namespace MilesApart\StaffBundle\Form\Products;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProductImageUploadType extends AbstractType
{
    private $product_id;
    

    public function __construct($product_id)
    {
        $this->product_id = $product_id;
    }

        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'hidden', array(
                'attr' => array(
                    'class'=> 'col-md-7 col-xs-12',
                ),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                
                'required'  => true,
            ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductImage'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundleProductImageUpload';
    }
}
