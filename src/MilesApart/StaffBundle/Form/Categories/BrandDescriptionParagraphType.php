<?php

namespace MilesApart\StaffBundle\Form\Categories;

use MilesApart\StaffBundle\Form\Categories\BrandDescriptionParagraphImageType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class BrandDescriptionParagraphType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand_description_paragraph_header', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Brand description paragraph header',
                'required'  => false,
            ));

        $builder
            ->add('brand_description_paragraph_text', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Brand description paragraph',
                'required'  => true,
            ));
        
        
        $builder
            ->add('file', 'file', array(
                'attr' => array(
                    'class'=> 'col-md-7'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Brand description paragraph image',
                'required'  => false,
            ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\BrandDescriptionParagraph'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_newbranddescriptionparagraph';
    }
}
