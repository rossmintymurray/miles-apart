<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class MarketingEmailType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marketing_email_subject_line', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Subject line',
                'required'  => true,
            ))

            ->add('marketing_email_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Marketing email name',
                'required'  => true,
            ))

            ->add('marketing_email_description', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Description',
                'required'  => true,
            ))
    
            ->add('marketing_email_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Email type',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:MarketingEmailType',
                'property' => 'marketing_email_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('marketing_email_type')
                                  ->orderBy('marketing_email_type.marketing_email_type_name', 'ASC');
                    },
            ))
            
            ->add('promotion', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Promotion',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Promotion',
                'property' => 'promotion_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('promotion')
                                  ->orderBy('promotion.promotion_name', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\MarketingEmail'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_marketingemail';
    }
}
