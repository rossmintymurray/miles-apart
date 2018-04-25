<?php

namespace MilesApart\PublicUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

use Symfony\Component\Security\Core\SecurityContext;

class ProfileFormType extends AbstractType
{
     /**
     * @var SecurityContext
     */
    protected $context;

    /**
     * @param SecurityContext $context
     */
    public function __construct($context)
    {
        $this->context = $context;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword')) {
            $constraint = new UserPassword();
        } else {
            // Symfony 2.1 support with the old constraint class
            $constraint = new OldUserPassword();
        }

        //parent::buildForm($builder, $options);
        $builder->remove('username');  // we use email as the username

        $builder
            ->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'label_attr' => array(
                    'class' => 'inline right'
                    ),
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => $constraint,
        ));

        $builder
            ->add('email', null, array(
                'label' => 'form.email',
                'label_attr' => array(
                    'class' => 'inline right'
                    ), 
                'translation_domain' => 'FOSUserBundle'
        ));

        if($this->context->getToken()->getUser()->getCustomer()->getPersonalCustomer() != null) {
            //Check if personal or business 
            $builder->add('personal_customer',new EditPersonalCustomerFormType(),array(
                'data_class' => 'MilesApart\AdminBundle\Entity\PersonalCustomer',
                'required' =>false,
                'mapped' => true,
            ));

        } else {
            //It's business so show business
            $builder->add('business_customer_representative',new EditBusinessCustomerRepresentativeFormType(),array(
                'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative',
                'required' =>false,
                'mapped' => true,
            ));
        }
    }

    public function getParent()
    {
       return 'fos_user_profile';
    }

    public function getName()
    {
        return 'milesapart_publicuser_profile';
    }
}
