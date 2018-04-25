<?php

namespace MilesApart\StaffBundle\Form\TransferRequests;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TransferRequestType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        

            $builder
            ->add('business_premises', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Business Premises',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:BusinessPremises',
                'property' => 'business_premises_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('business_premises')
                                  ->orderBy('buisness_premises.business_premises_name', 'ASC');
                    },
            ));
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
        return 'milesapart_staffbundle_transferrequest';
    }
}
