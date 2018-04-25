<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CustomerWishListProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer_wish_list', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Wish list',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:CustomerWishList',
                'property' => 'customer_wish_list_date_created', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('customer_wish_list')
                                  ->orderBy('customer_wish_list.customer_wish_list_date_created', 'ASC');
                    },
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
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\CustomerWishListProduct'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_customerwishlistproduct';
    }
}
