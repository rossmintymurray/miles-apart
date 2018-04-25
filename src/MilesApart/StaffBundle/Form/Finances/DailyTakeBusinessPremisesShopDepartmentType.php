<?php

namespace MilesApart\StaffBundle\Form\Finances;

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
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Department value',
                'required'  => true,
            ))

        ->add('shop_department', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
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

            ;
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
        return 'milesapart_staffbundle_dailytakebusinesspremisesshopdepartment';
    }
}