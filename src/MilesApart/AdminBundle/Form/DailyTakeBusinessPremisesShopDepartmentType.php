<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
class DailyTakeBusinessPremisesShopDepartmentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shop_department_value', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Department value',
                'required'  => true,
            ))
            
            ->add('shop_department', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Shop department',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:ShopDepartment',
                'property' => 'shop_department_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('shop_department')
                                  ->orderBy('shop_department.shop_department_name', 'ASC');
                    },
            ))
            

            ->add('daily_take_business_premises', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Daily take business premises',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:DailyTakeBusinessPremises',
                'property' => 'id', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('daily_take_business_premises')
                                  ->orderBy('daily_take_business_premises.id', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesShopDepartment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_dailytakebusinesspremisesshopdepartment';
    }
}
