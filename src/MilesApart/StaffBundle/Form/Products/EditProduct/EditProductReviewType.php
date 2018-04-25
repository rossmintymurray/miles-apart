<?php

namespace MilesApart\StaffBundle\Form\Products\EditProduct;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use MilesApart\StaffBundle\Form\Products\DataTransformer\CustomerToNameTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class EditProductReviewType extends AbstractType
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
            ->add('product_review_title', null, array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Review',
                'required'  => false,
            ));

        $builder
            ->add('product_review_content', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Review',
                'required'  => false,
            ));

         $builder
            ->add('product_review_rating', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Rating',
                'required'  => false,
            ));

        $builder
            ->add('product_review_approved', 'checkbox', array(
                'attr' => array(
                    'class'=> 'col-md-7 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Approved',
                'required'  => false,
            ));

        $builder
            ->add('product_review_date_created', 'date', array(
                'attr' => array(
                    'class'=> 'col-md-7'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Review date',
                'required'  => false,
            ));
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductReview'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_productreview';
    }
}
