<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class MarketingEmailSendListType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marketing_email', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Marketing email',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:MarketingEmail',
                'property' => 'marketing_email_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('marketing_email')
                                  ->orderBy('marketing_email.marketing_email_name', 'ASC');
                    },
            ))

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
      
        ;
    }
   
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\MarketingEmailSendList'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_marketingemailsendlist';
    }
}
