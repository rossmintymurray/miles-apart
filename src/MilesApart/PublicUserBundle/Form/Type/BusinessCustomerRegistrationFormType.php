<?php
namespace MilesApart\PublicUserBundle\Form\Type;

use MilesApart\PublicUserBundle\Form\Type\BusinessCustomerRepresentativeRegistrationFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Validator\Constraints;



class BusinessCustomerRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);

        $builder
            ->add('business_customer_name', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>''),
                'label'=>'Business Name',
                'required'  => true,
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
                
            ));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomer'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'empty_data' => function (FormInterface $form) {
                return new Blog($form->get('title')->getData());
            },
        ));
    }

    public function getName()
    {
        return 'milesapart_publicuser_businesscustomerregistration';
    }
}    


