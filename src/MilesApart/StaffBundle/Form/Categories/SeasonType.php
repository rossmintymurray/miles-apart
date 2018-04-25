<?php

namespace MilesApart\StaffBundle\Form\Categories;

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
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Season name',
                'required'  => true,
            ));

        $builder
            ->add('season_code', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Season code',
                'required'  => true,
            ));

        $builder
            ->add('season_background_colour', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Nav background colour',
                'required'  => true,
            ));
        
        $builder
            ->add('season_introduction', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Season introduction',
                'required'  => false,
            ));

        $builder
            ->add('file', 'file', array(
                'attr' => array(
                    'class'=> 'col-md-4'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Image',
                'required'  => true,
            ));

        $builder
            ->add('season_start_date','date', array(
                'attr' => array(
                    'label'=>'Season start date',
                    'class'=> 'col-md-7 col-sm-7 form_control_wrapper'
                ),
                'empty_value' => array(
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day'
                ),
                'widget' => 'choice',
                'input'=> 'datetime',
                'format' => 'dd  MMMM  yyyy',
                'years' => range(date('Y'), date('Y') +1),
                'label_attr'=> array('class'=>'col-md-4 col-sm-4 control-label'),
                'label'=>'Season start date',
                'required'  => true,
            ));

        $builder
            ->add('season_end_date','date', array(
                'attr' => array(
                    'label'=>'Season end date',
                    'class'=> 'col-md-7 col-sm-7 form_control_wrapper'
                ),
                'empty_value' => array(
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day'
                ),
                'widget' => 'choice',
                'input'=> 'datetime',
                'format' => 'dd  MMMM  yyyy',
                'years' => range(date('Y'), date('Y') +1),
                'label_attr'=> array('class'=>'col-md-4 col-sm-4 control-label'),
                'label'=>'Season end date',
                'required'  => true,
            ));

        $builder
            ->add('season_purchase_stock_start','date', array(
                'attr' => array(
                    'label'=>'Purchase stock start',
                    'class'=> 'col-md-7 col-sm-7 form_control_wrapper'
                ),
                'empty_value' => array(
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day'
                ),
                'widget' => 'choice',
                'input'=> 'datetime',
                'format' => 'dd  MMMM  yyyy',
                'years' => range(date('Y'), date('Y') +1),
                'label_attr'=> array('class'=>'col-md-4 col-sm-4 control-label'),
                'label'=>'Purchase stock start',
                'required'  => true,
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
        return 'milesapart_staffbundle_newseason';
    }
}
