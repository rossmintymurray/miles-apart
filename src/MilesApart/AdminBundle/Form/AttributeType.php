<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AttributeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attribute_name', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Attribute name',
                'required'  => true,
            ));

        $builder
            ->add('attribute_unit_of_measurement', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'empty_value' => 'Choose an option',
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Unit of measurement',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:AttributeUnitOfMeasurement',
                'property' => 'attribute_unit_of_measurement_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('attribute_unit_of_measurement')
                                  ->orderBy('attribute_unit_of_measurement.attribute_unit_of_measurement_name', 'ASC');
                    },
            ));
           
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Attribute'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_attribute';
    }
}
