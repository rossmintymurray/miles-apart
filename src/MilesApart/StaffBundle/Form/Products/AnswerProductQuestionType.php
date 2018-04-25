<?php

namespace MilesApart\StaffBundle\Form\Products;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use MilesApart\StaffBundle\Form\Products\DataTransformer\ProductQuestionToIdTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class AnswerProductQuestionType extends AbstractType
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
            ->add('product_question', 'hidden', array(
                
            ));

            $builder->get('product_question')
            ->addModelTransformer(new ProductQuestionToIdTransformer($this->objectManager));

        $builder
            ->add('product_answer_text', 'textarea', array(
                'attr' => array(
                    'class'=> 'col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-xs-12'),
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
        return 'milesapart_adminbundle_productanswer';
    }
}
