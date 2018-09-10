<?php
namespace MilesApart\PublicUserBundle\Form\Type;

use MilesApart\PublicUserBundle\Form\Type\BusinessCustomerRegistrationFormType;
use MilesApart\PublicUserBundle\Form\Type\PersonalCustomerRegistrationFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue;


class CustomerRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);
        
       $builder->remove('username');  // we use email as the username



       $builder->add('business_customer',new BusinessCustomerRegistrationFormType(),array(

                            'data_class' => null,
                            'required' =>false,
                            

                    )
                );

        $builder->add('personal_customer',new PersonalCustomerRegistrationFormType(),array(

                            'data_class' => null,
                            'required' =>false,
                            

                    )
                );



    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Customer'
        ));
    }


    public function getName()
    {
        return 'milesapart_publicuser_customerregistration';
    }
}    


