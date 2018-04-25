<?php
namespace MilesApart\PublicUserBundle\Form\Type;

use MilesApart\PublicUserBundle\Form\Type\CustomerRegistrationFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;


    


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);
        
       $builder->remove('username');  // we use email as the username

       $builder->add('personal_customer',new PersonalCustomerRegistrationFormType(),array(

                            'data_class' => 'MilesApart\AdminBundle\Entity\PersonalCustomer',
                            'required' =>false,
                            'mapped' => false,

                    )
                );

       $builder->add('business_customer_representative',new BusinessCustomerRepresentativeRegistrationFormType(),array(

                            'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative',
                            'required' =>false,
                            'mapped' => false,
                            
                    )
                );
    
        $builder->add('recaptcha', 'ewz_recaptcha', array(
            'attr'          => array(
                'options' => array(
                    'theme' => 'clean',
                    'theme' => 'light',
                    'type'  => 'image',
                    'size'  => 'normal'
                )
            ),
            'mapped' => false,
            'constraints'   => array(
                new RecaptchaTrue()
            ),
            'error_bubbling' => true
        ));

    }

    public function getParent()
    {
       return 'fos_user_registration';
    }


    public function getName()
    {
        return 'milesapart_publicuser_registration';
    }
}    


