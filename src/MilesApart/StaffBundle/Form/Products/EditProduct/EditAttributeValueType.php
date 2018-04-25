<?php

namespace MilesApart\StaffBundle\Form\Products\EditProduct;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EditAttributeValueType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
            ->add('attribute', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-4 form-control'),
            'class' => 'MilesApartAdminBundle:Attribute',
                    'property' => 'attribute_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('attribute')
                                  ->orderBy('attribute.attribute_name', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Attribute type',
            'empty_value' => 'Choose an option',
            ));

        $builder
            ->add('attribute_value', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Attribute value',
                'required'  => true,
            ));

        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\AttributeValue'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_attributevalueproduct';
    }
}
