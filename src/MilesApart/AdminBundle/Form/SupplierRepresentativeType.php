<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SupplierRepresentativeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('supplier', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'class' => 'MilesApartAdminBundle:Supplier',
                    'property' => 'supplier_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('supplier')
                                  ->orderBy('supplier.supplier_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Supplier'
            ));


        $builder->add('supplier_representative_start_date','date', array(
            'data' => new \DateTime('now'),
            'attr' => array(
                'label'=>'Start Date',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Start date',
            'required'  => false,
            ));

        $builder->add('supplier_representative_first_name',null, array(
            'attr' => array(
                'label'=>'First name',
                'class'=> 'col-md-7 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'First name',
            'required'  => true,
            ));

        $builder->add('supplier_representative_surname',null, array(
            'attr' => array(
                'label'=>'Surname',
                'class'=> 'col-md-7 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Surname',
            'required'  => true,
            ));

        $builder->add('supplier_representative_address_1',null, array(
            'attr' => array(
                'class'=> 'form-control col-md-7'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Address 1',
            'required'  => false,
            ));
        $builder->add('supplier_representative_address_2',null, array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Address 2',
            'required'  => false,
            ));
        $builder->add('supplier_representative_town',null, array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Town',
            'required'  => false,
            ));
        $builder->add('supplier_representative_county',null, array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'County',
            'required'  => false,
            ));
        $builder->add('supplier_representative_postcode',null, array(
            'attr' => array(
                'class'=> 'col-md-4 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Postcode',
            'required'  => false,
            ));
        $builder->add('supplier_representative_country',null, array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Country',
            'required'  => false,
            ));


        $builder->add('supplier_representative_email',null, array(
            'attr' => array(
                'label'=>'Email',
                'class'=> 'col-md-7 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Email address',
            'required'  => false,
            ));

        $builder->add('supplier_representative_mobile_number',null, array(
            'attr' => array(
                'label'=>'Mobile',
                'class'=> 'col-md-7 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Mobile number',
            'required'  => false,
            ));

        $builder->add('supplier_representative_landline_number',null, array(
            'attr' => array(
                'label'=>'Landline number',
                'class'=> 'col-md-7 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Landline number',
            'required'  => false,
            ));
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
        return 'milesapart_adminbundle_supplierrepresentative';
    }
}
