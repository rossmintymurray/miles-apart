<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PromotionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('promotion_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Promotion name',
                'required'  => true,
            ))

            ->add('promotion_landing_page', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Promotion name',
                'required'  => true,
            ))

            ->add('vanity_url_append_text', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Vanity URL append ',
                'required'  => true,
            ))

            ->add('promotion_start_date','date', array(
            'attr' => array(
                'label'=>'Start date',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -1),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Start date',
            'required'  => false,
            ))

            ->add('promotion_end_date','date', array(
            'attr' => array(
                'label'=>'End date',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -1),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'End date',
            'required'  => false,
            ))
            
            ->add('keyword', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Keyword',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Keyword',
                'property' => 'keyword_word', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('keyword')
                                  ->orderBy('keyword.keyword_word', 'ASC');
                    },
            ))

            ->add('promotion_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Promotion type',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:PromotionType',
                'property' => 'promotion_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('promotion_type')
                                  ->orderBy('promotion_type.promotion_type_name', 'ASC');
                    },
            ))

            ->add('traffic_source', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Traffic Source',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:TrafficSource',
                'property' => 'traffic_source_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('traffic_source')
                                  ->orderBy('traffic_source.traffic_source_name', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\Promotion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_promotion';
    }
}
