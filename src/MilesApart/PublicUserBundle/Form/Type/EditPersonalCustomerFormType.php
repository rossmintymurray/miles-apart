<?php
namespace MilesApart\PublicUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class EditPersonalCustomerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);

        $builder
            ->add('personal_customer_first_name', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => '[a-zA-Z]+'),
               'label_attr'=> array('class'=>'inline right'),
                'label'=>'First Name',
                'required'  => true,
                
            ));

        $builder
            ->add('personal_customer_surname', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => '[a-zA-Z]+'),
               'label_attr'=> array('class'=>'inline right'),
                'label'=>'Surname',
                'required'  => true,
            ));

        $builder
            ->add('personal_customer_email_opt_in', 'checkbox', array(
                'attr' => array(
                    'id' => 'checkbox1',
                    ),
                'label_attr'=> array('class'=>''),
                'label' => 'Email',
                'required'  => true,
            ));

            $builder
            ->add('personal_customer_phone_opt_in', 'checkbox', array(
                'attr' => array(
                    'id' => 'checkbox2',
                    ),
                'label_attr'=> array('class'=>''),
                'label' => 'Phone',
                'required'  => true,
            ));

            $builder
            ->add('personal_customer_text_opt_in', 'checkbox', array(
                'attr' => array(
                    'id' => 'checkbox3',
                    ),
                'label_attr'=> array('class'=>''),
                'label' => 'Text',
                'required'  => true,
            ));

            $builder
            ->add('personal_customer_post_opt_in', 'checkbox', array(
                'attr' => array(
                    'id' => 'checkbox4',
                    ),
                'label_attr'=> array('class'=>''),
                'label' => 'Post',
                'required'  => true,
            ));

            
        
       
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\PersonalCustomer'
        ));
    }

    public function getName()
    {
        return 'milesapart_publicuser_editpersonalcustomerform';
    }
}    


