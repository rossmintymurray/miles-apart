<?php

namespace MilesApart\StaffBundle\Form\Finances;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EmployeeStatutoryPaymentType extends AbstractType
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
                                
                                  ->orderBy('employee.employee_first_name', 'ASC');
                    },
            ))

         ->add('employee_statutory_payment_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Statutory Payment Type',
                'empty_value' => 'Select type...',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:EmployeeStatutoryPaymentType',
                'property' => 'employee_statutory_payment_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('employee_statutory_payment_type')
                                
                                  ->orderBy('employee_statutory_payment_type.employee_statutory_payment_type_name', 'ASC');
                    },
            ))

        

        ->add('employee_statutory_payment_value', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Total amount',
            ))

        

        ->add('employee_statutory_payment_week_end_date','date', array(
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
            'data_class' => 'MilesApart\AdminBundle\Entity\EmployeeStatutoryPayment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_employeestatutorypayment';
    }
}





