<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class MarketingEmailComponentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marketing_email_component_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Email component name',
                'required'  => true,
            ))

            ->add('marketing_email_component_content', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Email component content',
                'required'  => true,
            ))

            ->add('marketing_email_component_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Email component type',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:MarketingEmailComponentType',
                'property' => 'marketing_email_component_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('marketing_email_component_type')
                                  ->orderBy('marketing_email_component_type.marketing_email_component_type_name', 'ASC');
                    },
            ))

            ->add('marketing_email', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Marketing email',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:MarketingEmail',
                'property' => 'marketing_email', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('marketing_email')
                                  ->orderBy('marketing_email.marketing_email_name', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\MarketingEmailComponent'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_marketingemailcomponent';
    }
}
