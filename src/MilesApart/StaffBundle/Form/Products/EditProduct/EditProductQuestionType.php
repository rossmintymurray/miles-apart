<?php

namespace MilesApart\StaffBundle\Form\Products\EditProduct;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use MilesApart\StaffBundle\Form\Products\EditProduct\EditProductAnswerType;

use MilesApart\StaffBundle\Form\Products\DataTransformer\CustomerToNameTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class EditProductQuestionType extends AbstractType
{
    //Set up object manager for transformer
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('customer', 'hidden', array(
                
            ));

            $builder->get('customer')
            ->addModelTransformer(new CustomerToNameTransformer($this->objectManager));

        $builder
            ->add('product_question_text', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Question',
                'required'  => false,
            ));

        $builder
            ->add('product_question_date_created', 'date', array(
                'attr' => array(
                    'class'=> 'col-md-7'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Question date',
                'required'  => false,
                'data' => new \DateTime()
            ));

        //Set up category form
        $builder
            ->add('product_answer', 'collection', array(
                
                'type'          => new EditProductAnswerType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'product__answer__',
                'options'       => array(
                    'label'         => false,
                ),
                
                
            ));
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductQuestion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_productquestion';
    }
}
