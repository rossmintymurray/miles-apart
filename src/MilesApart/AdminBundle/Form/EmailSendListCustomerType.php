<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EmailSendListCustomerType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email_send_list', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Email send list',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:EmailSendList',
                'property' => 'email_send_list_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('email_send_list')
                                  ->orderBy('email_send_list.email_send_list_name', 'ASC');
                    },
            ))
            
            ->add('customer', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Customer',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CompetitorType',
                'property' => 'id', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer')
                                  ->orderBy('customer.id', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\EmailSendListCustomer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_emailsendlistcustomer';
    }
}
