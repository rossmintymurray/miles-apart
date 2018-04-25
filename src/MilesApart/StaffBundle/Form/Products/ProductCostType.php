<?php

namespace MilesApart\StaffBundle\Form\Products;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProductCostType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_cost_value', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-3 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Cost',
                'required'  => true,
            ));


            $builder
            //Add the supplier
           ->add('product_supplier', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Supplier',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Supplier',
                'property' => 'supplier_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('supplier')
                                  ->orderBy('supplier.supplier_name', 'ASC');
                    },
            ))

            
        ;
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductCost'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_productcost';
    }
}
