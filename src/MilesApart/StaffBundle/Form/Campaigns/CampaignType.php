<?php

namespace MilesApart\StaffBundle\Form\Campaigns;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CampaignType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campaign_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Campaign name',
                'required'  => true,
            ))

            ->add('campaign_start_date','date', array(
            'attr' => array(
                'label'=>'Start date',
                'class'=> 'col-md-4 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') +1),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Start date',
            'required'  => false,
            ))

            ->add('campaign_end_date','date', array(
            'attr' => array(
                'label'=>'End date',
                'class'=> 'col-md-4 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') +1),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'End date',
            'required'  => false,
            ))

            ->add('campaign_introduction', "textarea", array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Campaign introduction',
                'required'  => true,
            ))

            ->add('campaign_description', "textarea", array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Campaign description',
                'required'  => true,
            ))

            ->add('campaign_objective', "textarea", array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Campaign objectives',
                'required'  => true,
            ))

            ->add('campaign_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Campaign type',
                'empty_value' => 'Please select one',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CampaignType',
                'property' => 'campaign_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('campaign_type')
                                  ->orderBy('campaign_type.campaign_type_name', 'ASC');
                    },
            ))

            //Add the promotion 
            ->add('promotion', 'collection', array(
                
                'type'          => new PromotionType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'promotion__name__',
                'options'       => array(),
                'label'         => false,

                
                
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Campaign'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_campaign';
    }
}
