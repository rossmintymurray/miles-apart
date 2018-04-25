<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EmployeeWageRateJobRoleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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

            ->add('employee_wage_rate', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Wage rate',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:EmployeeWageRate',
                'property' => 'employee_wage_rate_hourly_rate', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('employee_wage_rate')
                                  ->orderBy('employee_wage_rate.employee_wage_rate_hourly_rate', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\EmployeeWageRateJobRole'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_employeewageratejobrole';
    }
}
