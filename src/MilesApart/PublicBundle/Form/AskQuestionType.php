<?php

namespace MilesApart\PublicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use MilesApart\PublicBundle\Form\DataTransformer\ProductToIdTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class AskQuestionType extends AbstractType
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
            ->add('product', 'hidden', array(
            ));

         $builder->get('product')
            ->addModelTransformer(new ProductToIdTransformer($this->objectManager));

        $builder
            ->add('question_name', null, array(
                'attr' => array(
                    'type'=> 'text',
                    ),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Name',
                'required'  => true,
                'mapped' => false
            ));

        $builder
            ->add('question_email', null, array(
                'attr' => array(
                    'type'=> 'email'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Email',
                'required'  => true,
                'mapped' => false
            ));

        $builder
            ->add('product_question_text', "textarea", array(
                'attr' => array(
                    'type'=> 'text',
                    'style'=> 'height:7rem'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Question',
                'required'  => true,
            ));

        $builder->add('recaptcha', 'ewz_recaptcha', array(
            'attr'          => array(
                'options' => array(
                    'theme' => 'clean',
                    'theme' => 'light',
                    'type'  => 'image',
                    'size'  => 'normal'
                )
            ),
            'label' => false,
            'mapped' => false,
            'constraints'   => array(
                new RecaptchaTrue()
            ),
            'error_bubbling' => true
        )); 
    }
    
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
        return 'milesapart_publicbundle_question';
    }
}
