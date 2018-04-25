<?php

namespace MilesApart\PublicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;

class FeedbackFormType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('feedback_name', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Name',
                'required'  => true,
            ));

        $builder
            ->add('feedback_email_address', null, array(
                'attr' => array(
                    'type'=> 'email'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Email',
                'required'  => true,
            ));

        $builder
            ->add('feedback_content', "textarea", array(
                'attr' => array(
                    'type'=> 'text',
                    'style'=> 'height:7rem'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Feedback',
                'required'  => true,
            ));
        
        $builder->add('recaptcha', 'ewz_recaptcha', array( 
                'attr'=> array(
                    'options' => array(
                        'theme' => 'light',
                        'type' => 'image'
                    )
                )
            ));                       
        
    
    }
    
     public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\PublicBundle\Entity\Feedback'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_publicbundle_contactusmessage';
    }
}
