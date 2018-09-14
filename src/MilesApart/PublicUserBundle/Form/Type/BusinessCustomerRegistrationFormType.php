<?php
namespace MilesApart\PublicUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BusinessCustomerRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('business_customer_name', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>''),
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
            'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomer',
            'validation_groups' => function(FormInterface $form) {
                ladybug_dump($form->getParent()->getParent()->get('is_customer_business')->getData());

                $data = $form->getParent()->getParent()->get('is_customer_business')->getData();
                if ($data === true) {
                    return array('business_customer');
                } else {
                    return array('personal_customer');
                }
            }
        ));
    }

    public function getName()
    {
        return 'milesapart_publicuser_businesscustomerregistration';
    }
}    


