<?php

namespace MilesApart\PublicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class ContactUsMessageType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('contact_us_message_name', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Name',
                'required'  => true,
                'constraints' => array(
                    new NotBlank(),
                ),
            ));

        $builder
            ->add('contact_us_message_email_address', null, array(
                'attr' => array(
                    'type'=> 'email'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Email',
                'required'  => true,
                'constraints' => array(
                    new NotBlank(),
                    new Email(),
                ),
            ));

        $builder
            ->add('contact_us_message_content', "textarea", array(
                'attr' => array(
                    'type'=> 'text',
                    'style'=> 'height:7rem'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Message',
                'required'  => true,
            ));

        $builder->add('recaptcha', 'ewz_recaptcha', array( 
                'attr'=> array(
                    'options' => array(
                        'theme' => 'light',
                        'type' => 'image',
                    )
                ),
                'error_bubbling' => false,
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
