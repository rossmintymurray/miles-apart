<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EmployeeHolidayType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('employee_holiday_start_date','date', array(
            'attr' => array(
                'label'=>'Holiday start',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -1),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Holiday start',
            'required'  => false,
            ))

            ->add('employee_holiday_end_date','date', array(
            'attr' => array(
                'label'=>'Holiday end',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -65),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Holiday end',
            'required'  => false,
            ))
            
            ->add('employee_holiday_authorised', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Authorised',
                'required'  => true,
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

            ->add('employee_holiday_authorised_by', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Authorised by',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:AdminUser',
                'property' => 'id', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('admin_user')
                                  ->orderBy('admin_user.id', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\EmployeeHoliday'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_employeeholiday';
    }
}
