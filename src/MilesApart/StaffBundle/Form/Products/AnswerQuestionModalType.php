<?php

namespace MilesApart\StaffBundle\Form\Products;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use MilesApart\PublicBundle\Form\DataTransformer\ProductQuestionToIdTransformer;
use Doctrine\Common\Persistence\ObjectManager;


class AnswerQuestionModalType extends AbstractType
{
    
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
            ->add('product_question', 'hidden', array(
            ));

         $builder->get('product_question')
            ->addModelTransformer(new ProductToIdTransformer($this->objectManager));

        $builder
            ->add('product_answer_text', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-md-3 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-xs-12 control-label'),
                'label'=>'Answer',
                'required'  => false,
            ));
            
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductAnswer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_productanswer';
    }
}
