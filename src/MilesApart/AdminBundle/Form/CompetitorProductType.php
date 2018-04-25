<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class CompetitorProductType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('competitor_product_current_price', 'number', array(
                'precision'=>2,
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Competitor price',
                'required'  => true,
            ));

         $builder
            ->add('competitor', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Competitor',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Competitor',
                'property' => 'competitor_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('competitor')
                                  ->orderBy('competitor.competitor_name', 'ASC');
                    },
            ));

         $builder
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
            ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\CompetitorProduct'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_competitorproduct';
    }
}
