<?php
namespace MilesApart\PublicUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints;



class BusinessCustomerRepresentativeRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('business_customer',new BusinessCustomerRegistrationFormType(),array(
                'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomer',
                'required' =>false,
            ));

        $builder
            ->add('business_customer_representative_first_name', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>''),
                'label'=>'First Name',
                'required'  => true,
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
                ));

        $builder
            ->add('business_customer_representative_surname', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>''),
                'label'=>'Surname',
                'required'  => true,
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
            ));
        
        $builder
            ->add('business_customer_representative_email_opt_in', 'checkbox', array(
                'attr' => array(
                    'id' => 'checkbox1',
                    ),
                'label_attr'=> array('class'=>''),
                'label' => false,
                'required'  => false,
            ));

            $builder
            ->add('business_customer_representative_phone_opt_in', 'checkbox', array(
                'attr' => array(
                    'id' => 'checkbox2',
                    ),
                'label_attr'=> array('class'=>''),
                'label' => false,
                'required'  => false,
            ));

            $builder
            ->add('business_customer_representative_text_opt_in', 'checkbox', array(
                'attr' => array(
                    'id' => 'checkbox3',
                    ),
                'label_attr'=> array('class'=>''),
                'label' => false,
                'required'  => false,
            ));

            $builder
            ->add('business_customer_representative_post_opt_in', 'checkbox', array(
                'attr' => array(
                    'id' => 'checkbox4',
                    ),
                'label_attr'=> array('class'=>''),
                'label' => false,
                'required'  => false,
            ));

            $builder
            ->add('business_customer_representative_terms_agreed', 'checkbox', array(
                'attr' => array(
                    
                    ),
                'label_attr'=> array('class'=>''),
                'label' => "I have read and agree to the terms and privacy policy.",
                'required'  => false,
            ));
        
        
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative'
        ));
    }

    public function getName()
    {
        return 'milesapart_publicuser_businesscustomerrepresentativeregistration';
    }
}    


