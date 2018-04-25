<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PostageBandDispatchLogisticsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('postage_band_price', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Postage price',
                'required'  => true,
            ))

            ->add('postage_band_price_effective_date','date', array(
            'attr' => array(
                'label'=>'Price effective date',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -1),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Price effective date',
            'required'  => false,
            ))

            ->add('logistics_company', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Logistics company',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:LogisticsCompany',
                'property' => 'logistics_company_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('logistics_company')
                                  ->orderBy('logistics_company.logistics_company_name', 'ASC');
                    },
            ))

            ->add('postage_band', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Postage band',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:PostageBand',
                'property' => 'postage_band_max_weight', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('postage_band')
                                  ->orderBy('postage_band.postage_band_max_weight', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_postagebanddispatchlogistics';
    }
}
