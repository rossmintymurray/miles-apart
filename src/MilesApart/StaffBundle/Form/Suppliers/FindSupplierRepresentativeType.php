<?php

namespace MilesApart\StaffBundle\Form\Suppliers;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FindSupplierRepresentativeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('supplier_representative_full_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Supplier Representative Name',
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
            'data_class' => 'MilesApart\AdminBundle\Entity\SupplierRepresentative'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundlefindsupplierrepresentative';
    }
}
