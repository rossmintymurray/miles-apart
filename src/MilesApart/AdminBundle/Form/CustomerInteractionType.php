<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CustomerInteractionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer_interaction_date_resolved','date', array(
            'attr' => array(
                'label'=>'Interaction resolved',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -1),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Interaction resolved',
            'required'  => true,
            ))

            ->add('customer_interaction_notes', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Interaction notes',
                'required'  => true,
            ))

            ->add('customer', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Customer',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Customer',
                'property' => 'id', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer')
                                  ->orderBy('customer.id', 'ASC');
                    },
            ))

            ->add('customer_interaction_reason', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Interaction reason',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CustomerInteractionReason',
                'property' => 'customer_interaction_reason_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer_interaction')
                                  ->orderBy('customer_interaction.customer_interaction_reason_name', 'ASC');
                    },
            ))

            ->add('customer_interaction_resolution', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Interaction resolution',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CustomerInteractionResolution',
                'property' => 'customer_interaction_resolution_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer_interaction_resolution')
                                  ->orderBy('customer_interaction_resolution.customer_interaction_resolution_name', 'ASC');
                    },
            ))

            ->add('customer_interaction_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Interaction type',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CustomerInteractionType',
                'property' => 'customer_interaction_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer_interaction_type')
                                  ->orderBy('customer_interaction_type.customer_interaction_type_name', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\CustomerInteraction'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_customerinteraction';
    }
}
