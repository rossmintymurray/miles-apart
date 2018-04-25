<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SeasonType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('season_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Season name',
                'required'  => true,
            ));

        $builder
            ->add('season_start_date','date', array(
            'attr' => array(
                'label'=>'Start date',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
              'years' => range(date('Y'), date('Y') +2),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Start date',
            'required'  => true,
            ));

        $builder
            ->add('season_end_date','date', array(
            'attr' => array(
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') +2),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'End date',
            'required'  => true,
            ));

        $builder
            ->add('season_purchase_stock_start','date', array(
            'attr' => array(
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') +2),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Purchase stock start',
            'required'  => true,
            ));

        $builder
            ->add('season_purchase_stock_deadline','date', array(
            'attr' => array(
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') +2),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Purchase stock deadline',
            'required'  => true,
            ));

        $builder
            ->add('season_image_path', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Image',
                'required'  => false,
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Season'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_season';
    }
}
