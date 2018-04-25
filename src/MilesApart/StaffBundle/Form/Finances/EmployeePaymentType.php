<?php

namespace MilesApart\StaffBundle\Form\Finances;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EmployeePaymentType extends AbstractType
{

    public $daily_take_date;
    public $employee_payment_week_end_date;

    public function __construct($daily_take_date)
    {
        $this->daily_take_date = $daily_take_date;
        //Get the payment week end date from the date of the daily take.
        $this->employee_payment_week_end_date = date('Y-m-d',strtotime('Saturday this week', strtotime($daily_take_date->format('Y-m-d'))));
         
        //$week_end_date = explode("-", $week_end_date);

        $this->employee_payment_week_end_date = new \DateTime($this->employee_payment_week_end_date);
        


    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
         ->add('employee', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Employee',
                'empty_value' => 'Select employee...',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Employee',
                'property' => 'employee_full_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('employee')
                                    ->where('employee.employee_leaving_date IS null')
                                  ->orderBy('employee.employee_first_name', 'ASC');
                    },
            ))

        ->add('employee_payment_total_hours', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control employee_payment_total_hours_input',
                    'autocomplete' => 'off'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Total hours',
                'required'  => true,
            ))

        ->add('employee_payment_total', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Total amount',
                'disabled'  => true,
            ))

        ->add('is_employee_payment_holiday', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Is holiday pay?',
                'required'  => false,
            ))
         

        ->add('employee_payment_week_end_date','date', array(
            'attr' => array(
                'label'=> 'Payment week ending',
                'class'=> 'col-md-7 col-sm-7 form_control_wrapper'),
              //'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
            
              'widget' => 'choice',
              'input'=> 'datetime',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -1),
               //'days' => array(1,3),
               'data'=> $this->employee_payment_week_end_date,
            'label_attr'=> array('class'=>'col-md-4 col-sm-4 control-label'),
            'label'=> 'Payment week ending',
            'required'  => true,
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
            'data_class' => 'MilesApart\AdminBundle\Entity\EmployeePayment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_employeepayment';
    }
}