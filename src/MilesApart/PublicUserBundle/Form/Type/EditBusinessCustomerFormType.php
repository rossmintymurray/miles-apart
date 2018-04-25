<?php
namespace MilesApart\PublicUserBundle\Form\Type;

use MilesApart\PublicUserBundle\Form\Type\CustomerRegistrationFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class EditBusinessCustomerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('business_customer_name', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => '[a-zA-Z]+'),
                'label_attr'=> array('class'=>'inline right'),
                'label'=>'Business Name',
                'required'  => true,
                
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

    public function getName()
    {
        return 'milesapart_publicuser_editbusinesscustomertype';
    }
}    


