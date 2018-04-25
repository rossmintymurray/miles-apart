<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class TransferRequestType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transfer_request_date_completed','date', array(
            'attr' => array(
                'label'=>'Date of completion',
                'class'=> 'col-md-7 form_control_wrapper'),
              'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
              'widget' => 'choice',
              'format' => 'dd  MMMM  yyyy',
               'years' => range(date('Y'), date('Y') -65),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Date of completion',
            'required'  => false,
            ))

            ->add('business_premises', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Business premises',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:BusinessPremises',
                'property' => 'business_premises_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('business_premises')
                                  ->orderBy('business_premises.business_premises_name', 'ASC');
                    },
            ))

            ->add('transfer_request_state', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Transfer request state',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:TransferRequestState',
                'property' => 'transfer_request_state_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('transfer_request_state')
                                  ->orderBy('transfer_request_state.transfer_request_state_name', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\TransferRequest'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_transferrequest';
    }
}
