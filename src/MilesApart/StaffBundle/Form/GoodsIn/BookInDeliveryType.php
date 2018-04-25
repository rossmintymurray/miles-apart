<?php

namespace MilesApart\StaffBundle\Form\GoodsIn;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class BookInDeliveryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('logistics_company', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-4 col-xs-12 form-control'),
            'class' => 'MilesApartAdminBundle:LogisticsCompany',
                    'property' => 'logistics_company_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('logistics_company')
                                  ->orderBy('logistics_company.logistics_company_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Carrier',
            ));

        $builder
            ->add('supplier', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-4 col-xs-12 form-control'),
            'class' => 'MilesApartAdminBundle:Supplier',
                    'property' => 'supplier_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('supplier')
                                  ->orderBy('supplier.supplier_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Supplier',
            ));

        $builder
            ->add('business_premises', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-4 col-xs-12 form-control'),
            'class' => 'MilesApartAdminBundle:BusinessPremises',
                    'property' => 'business_premises_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('business_premises')
                                  ->orderBy('business_premises.business_premises_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Deliver to',
            ));

        $builder
        ->add('booked_in_date','date', array(
            'attr' => array(
                'label'=>'Booked date',
                'class'=> 'col-md-7 col-xs-12 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day' ),
              'widget' => 'choice',
              'input'=> 'datetime',
              'format' => 'dd  MM  yyyy',
               'years' => range(date('Y'), date('Y') -1),
            'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
            'label'=>'Booked date',
            'required'  => true,

            ))
            ;

            


        $builder
            ->add('booked_in_before_12', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Before 12?',
                'required'  => false,
                
            ));

            $builder
            ->add('contact_name', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Contact name',
                'required'  => false,
            ));

            $builder
            ->add('contact_phone_number', 'text', array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Contact number',
                'required'  => false,
            ));

            $builder
            ->add('supplier_delivery_notes', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Notes',
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
            'data_class' => 'MilesApart\AdminBundle\Entity\SupplierDelivery'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_newdelivery';
    }
}