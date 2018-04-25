<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class BusinessPremisesType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('business_premises_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Premises name',
                'required'  => true,
            ));

            $builder
            ->add('business_premises_code', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Premises name',
                'required'  => true,
            ));

        $builder
            ->add('business_premises_address_line_1', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Address line 1',
                'required'  => true,
            ));

        $builder
            ->add('business_premises_address_line_2', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Address line 2',
                'required'  => false,
            ));

        $builder
            ->add('business_premises_town', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Town',
                'required'  => true,
            ));
            
        $builder
            ->add('business_premises_county', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'County',
                'required'  => true,
            ));

        $builder
            ->add('business_premises_postcode', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Postcode',
                'required'  => true,
            ));

        $builder
            ->add('business_premises_phone', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Phone',
                'required'  => true,
            ));

        $builder
            ->add('business_premises_email', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Email',
                'required'  => true,
            ));

        $builder
            ->add('business_premises_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Premises type',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:BusinessPremisesType',
                'property' => 'business_premises_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('business_premises_type')
                                  ->orderBy('business_premises_type.business_premises_type_name', 'ASC');
                    },
            ));

        $builder
            ->add('business', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Business',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Business',
                'property' => 'business_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('business')
                                  ->orderBy('business.business_name', 'ASC');
                    },
            ));
    }
    
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\BusinessPremises'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_businesspremises';
    }
}
