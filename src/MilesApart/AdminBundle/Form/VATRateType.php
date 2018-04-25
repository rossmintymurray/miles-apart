<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class VATRateType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('vat_rate_value', 'number', array(
                'precision'=>2,
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Type name',
                'required'  => true,
            ));

        $builder->add('vat_effective_date','date', array(
            'attr' => array(
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -65),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'VAT effective date',
            'required'  => false,
            ));

        $builder
            ->add('vat_rate_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'VAT rate type',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:VATRateType',
                'property' => 'vat_rate_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('vat_rate_type')
                                  ->orderBy('vat_rate_type.vat_rate_type_name', 'ASC');
                    },
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\VATRate'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_vatrate';
    }
}
