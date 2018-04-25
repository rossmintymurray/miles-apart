<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
class AdminUserTypeAccessRightType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('admin_user_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Admin user type',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:AdminUserType',
                'property' => 'admin_user_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('admin_user_type')
                                  ->orderBy('admin_user_type.admin_user_type_name', 'ASC');
                    },
            ))
            ->add('access_right', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Access right',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:AccessRight',
                'property' => 'access_right_action', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('access_right')
                                  ->orderBy('access_right.access_right_action', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\AdminUserTypeAccessRight'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_adminusertypeaccessright';
    }
}
