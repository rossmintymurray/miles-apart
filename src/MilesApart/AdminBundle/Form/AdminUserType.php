<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AdminUserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('username', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Username',
                'required'  => true,
            ));

        $builder
            ->add('password', 'password', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Password',
                'required'  => true,
            ));

        $builder
            ->add('employee', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Employee',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Employee',
                'property' => 'employee_full_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('employee')
                        ->where('employee.employee_leaving_date is null')
                                  ->orderBy('employee.employee_surname', 'ASC');
                    },
            ));

        $builder
            ->add('admin_user_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'User type',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:AdminUserType',
                'property' => 'admin_user_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('admin_user_type')
                                  ->orderBy('admin_user_type.admin_user_type_name', 'ASC');
                    },
            ));
        
        $builder
            ->add('is_active', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Is active?',
                'required'  => true,
            ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\AdminUser'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_adminuser';
    }
}
