<?php

namespace MilesApart\StaffBundle\Form\HR;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EmployeeJobRoleEmployeeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('employee_job_role_date_commenced','date', array(
            'attr' => array(
                'label'=>'Job role commenced',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -10),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Job role commenced',
            'required'  => false,
            ))

            ->add('employee_job_role', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Job role',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:EmployeeJobRole',
                'property' => 'employee_job_role_title', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('employee_job_role')
                                  ->orderBy('employee_job_role.employee_job_role_title', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\EmployeeJobRoleEmployee'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_employeejobroleemployee';
    }
}
