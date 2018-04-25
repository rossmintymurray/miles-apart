<?php

namespace MilesApart\StaffBundle\Form\HR;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use MilesApart\StaffBundle\Form\HR\EmployeeJobRoleEmployeeType;
use MilesApart\StaffBundle\Form\HR\EmployeeContractedHoursType;
class EmployeeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employee_first_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'First name',
                'required'  => true,
            ));

        $builder
            ->add('employee_surname', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Surname',
                'required'  => true,
            ));

        $builder
            ->add('employee_address_1', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Address line 1',
                'required'  => true,
            ));

        $builder
            ->add('employee_address_2', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Address line 2',
                'required'  => false,
            ));

        $builder
            ->add('employee_town', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Town',
                'required'  => true,
            ));
            
        $builder
            ->add('employee_county', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'County',
                'required'  => true,
            ));

        $builder
            ->add('employee_postcode', null, array(
                'attr' => array(
                    'class'=> 'col-md-1 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Postcode',
                'required'  => true,
            ));

        $builder
            ->add('employee_landline_phone', null, array(
                'attr' => array(
                    'class'=> 'col-md-2 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Landline phone',
                'required'  => false,
            ));

        $builder
            ->add('employee_mobile_phone', null, array(
                'attr' => array(
                    'class'=> 'col-md-2 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Mobile phone',
                'required'  => true,
            ));

        $builder
            ->add('employee_email', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Email address',
                'required'  => false,
            ));

        $builder
            ->add('employee_national_insurance_number', null, array(
                'attr' => array(
                    'class'=> 'col-md-2 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'NI number',
                'required'  => false,
            ));

        

        $builder->add('employee_date_of_birth','date', array(
            'attr' => array(
                'label'=>'Date of birth',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -65),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Date of birth',
            'required'  => false,
            ));

        $builder->add('employee_starting_date','date', array(
            
            'attr' => array(
                'label'=>'Starting Date',
                'class'=> 'col-md-7 form_control_wrapper'),
                'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Starting date',
            'required'  => false,
            ));

        $builder->add('employee_leaving_date','date', array(
            
            'attr' => array(
                'label'=>'Leaving Date',
                'class'=> 'col-md-7 form_control_wrapper'),
                'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Leaving date',
            'required'  => false,
            ));

        $builder
            ->add('employee_tax_code', null, array(
                'attr' => array(
                    'class'=> 'col-md-1 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Tax code',
                'required'  => false,
            ));

        $builder
        //Add the job role 
            ->add('employee_job_role_employee', 'collection', array(
                
                'type'          => new EmployeeJobRoleEmployeeType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'employee__job__role__employee__name__',
                'options'       => array(
                    'label'         => false,
                ),

                
                
            ));

        $builder
        //Add the contracted hours 
            ->add('employee_contracted_hours', 'collection', array(
                
                'type'          => new EmployeeContractedHoursType(),
                'allow_add'     => true,
                'allow_delete'  => false,
                'prototype'     => true,
                'prototype_name' => 'employee__contracted__hours__',
                'options'       => array(
                    'label'         => false,
                ),

                
                
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Employee'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_employee';
    }
}
