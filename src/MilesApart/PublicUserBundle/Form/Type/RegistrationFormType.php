<?php
namespace MilesApart\PublicUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);
        
        $builder->remove('username');  // we use email as the username

        $builder
            ->add('is_customer_business', 'checkbox', array(
                'attr' => array(
                    'class' => 'business_customer_toggle',
                ),
                'label_attr'=> array('class'=>''),
                'label' => false,
                'required' => false,
                'empty_data' => null
            ));

        $builder
            ->add('business_customer_representative', new BusinessCustomerRepresentativeRegistrationFormType(), array(
                'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative',
                'required' => true,
                'mapped' => true,
                'cascade_validation' => true
            ));

        $builder
            ->add('personal_customer', new PersonalCustomerRegistrationFormType(), array(
                'data_class' => 'MilesApart\AdminBundle\Entity\PersonalCustomer',
                'required' => true,
                'mapped' => true,
                'cascade_validation' => true
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
            'mapped' => false,
            'constraints'   => array(
                new RecaptchaTrue()
            ),
            'error_bubbling' => false
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'cascade_validation' => true,
            'validation_groups' => function(FormInterface $form) {
                $data = $form->getData();
                if ($data->get('is_customer_business') === true) {
                    return array('business_customer');
                } else {
                    return array('personal_customer');
                }
            },
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


