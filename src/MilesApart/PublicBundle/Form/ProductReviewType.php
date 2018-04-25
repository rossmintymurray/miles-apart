<?php

namespace MilesApart\PublicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use MilesApart\PublicBundle\Form\DataTransformer\ProductToIdTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class ProductReviewType extends AbstractType
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
            ->add('product_review_title', null, array(
                'attr' => array(
                    'type'=> 'text',
                    ),
                'label_attr'=> array('class'=>''),
                'label'=>'Review title',
                'required'  => true,
                'mapped' => false
            ));

        $builder
            ->add('product_review_rating', null, array(
                'attr' => array(
                    'type'=> 'email'),
                'label_attr'=> array('class'=>''),
                'label'=>'Rating',
                'required'  => true,
                'mapped' => false
            ));

        $builder
            ->add('product_review_content', "textarea", array(
                'attr' => array(
                    'type'=> 'text',
                    'style'=> 'height:7rem'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your review',
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
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductReview'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_publicbundle_review';
    }
}
