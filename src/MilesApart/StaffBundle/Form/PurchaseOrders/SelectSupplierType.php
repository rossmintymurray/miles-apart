<?php

namespace MilesApart\StaffBundle\Form\PurchaseOrders;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SelectSupplierType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //Add the season
           ->add('supplier', 'entity', array(
                'attr' => array(
                    'class'=> 'col-xs-12 col-md-4 form-control'),
                'label_attr'=> array('class'=>'hidden-xs col-md-4 control-label'),
                'label'=>'Supplier',
                'empty_value' => 'Please select one...',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Supplier',
                'property' => 'supplier_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('supplier')
                                  ->orderBy('supplier.supplier_name', 'ASC');
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
            'data_class' => 'MilesApart\StaffBundle\Entity\PurchaseOrders\SelectSupplier'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_purchaseordersselectsupplier';
    }
}
