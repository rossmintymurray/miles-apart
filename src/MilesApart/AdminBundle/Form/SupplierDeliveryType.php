<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SupplierDeliveryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('booked_in_datetime', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Booked in date/time',
                'required'  => true,
            ))

            ->add('contact_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Contact name',
                'required'  => true,
            ))

            ->add('contact_phone_number', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Contact number',
                'required'  => true,
            ))

            ->add('delivered_datetime', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Delivered date/time',
                'required'  => true,
            ))

            ->add('supplier', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
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

            ->add('logistics_company', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Logistics company',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:LogisticsCompany',
                'property' => 'logistics_company_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('logistics_company')
                                  ->orderBy('logistics_company.logistics_company_name', 'ASC');
                    },
            ))

            ->add('business_premises', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Business premises',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:BusinessPremises',
                'property' => 'business_premises_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('business_premises')
                                  ->orderBy('business_premises.business_premises_name', 'ASC');
                    },
            ))

            ->add('employee', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Employee',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Employee',
                'property' => 'employee_surname', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('employee')
                                  ->orderBy('employee.employee_surname', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\SupplierDelivery'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_supplierdelivery';
    }
}
