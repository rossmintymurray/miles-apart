<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SelectCategoryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
        $builder
            ->add('select_category', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'class' => 'MilesApartAdminBundle:Category',
                    'property' => 'category_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('category')
                                  ->orderBy('category.category_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Category',
            ));

        //Set up category form
        $builder
            ->add('category', 'collection', array(
                
                'type'          => new CategoryType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'category__name__',
                'options'       => array(),
                
                
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_selectcategory';
    }
}