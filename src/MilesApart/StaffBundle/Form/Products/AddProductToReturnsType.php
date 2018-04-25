<?php

namespace MilesApart\StaffBundle\Form\Products;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

use MilesApart\StaffBundle\Form\DataTransformer\ProductToNameTransformer;

class AddProductToReturnsType extends AbstractType
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityManager = $this->em;
        $transformer = new ProductToNameTransformer($entityManager);

        $builder
            ->add('product_barcode', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-3 col-xs-12 form-control add_product_to_list_product_barcode'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Barcode',
                'required'  => false,
            ));

            $builder
            ->add(
                $builder
                ->create(
                    'product_name', 'text', array(
                        'attr' => array('class'=> 'col-md-3 col-xs-12 form-control add_product_to_list_product_name'),
                        'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                        'label'=>'Search product',
                        'required'  => false,
                    )
                )
                ->addModelTransformer($transformer)
            );
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
        return 'milesapart_staffbundle_addproducttoreturns';
    }
}
