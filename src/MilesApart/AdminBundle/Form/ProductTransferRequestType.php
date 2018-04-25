<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProductTransferRequestType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_transfer_request_qty', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Request qty',
                'required'  => true,
            ))
            
            ->add('product', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Product',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Product',
                'property' => 'product_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('product')
                                  ->orderBy('product.product_name', 'ASC');
                    },
            ))

            ->add('transfer_request', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Transfer request',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:TransferRequest',
                'property' => 'transfer_request_date_created', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('transfer_request')
                                  ->orderBy('transfer_request.transfer_request_date_created', 'ASC');
                    },
            ))

            ->add('product_transfer_request_state', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Transfer request state',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:ProductTransferRequestState',
                'property' => 'product_transfer_request_state_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('product_transfer_request_state')
                                  ->orderBy('product_transfer_request_state.product_transfer_request_state_name', 'ASC');
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
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductTransferRequest'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_producttransferrequest';
    }
}
