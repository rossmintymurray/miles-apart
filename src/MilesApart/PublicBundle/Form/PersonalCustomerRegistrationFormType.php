<?php





namespace MilesApart\PublicBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class PersonalCustomerRegistrationFormType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        

        $builder
            ->add('personal_customer_first_name', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>''),
                'label'=>'First Name',
                'required'  => true,
            ));

        $builder
            ->add('personal_customer_surname', null, array(
                'attr' => array(
                    'type'=> 'text',
                    'pattern' => 'alpha'),
                'label_attr'=> array('class'=>''),
                'label'=>'Surname',
                'required'  => true,
            ));

        $builder
            ->add('personal_customer_date_of_birth','date', array(
                'attr' => array(
                    'label'=>'Date of Birth',
                    'class'=> ''),
                  'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                  'widget' => 'choice',
                  'input'=> 'datetime',
                  'format' => 'dd  MMMM  yyyy',
                   'years' => range(date('Y'), date('Y') -110),
                'label_attr'=> array('class'=>''),
                'label'=>'Date of Birth',
                'required'  => true,
                ))
            ;

        $builder
            ->add('password', 'password', array(
                'attr' => array(
                    'type'=> 'password'),
                'label_attr'=> array('class'=>''),
                'label'=>'Your Password',
                'required'  => true,
            ));
    }
    
    
    

    public function getParent()
    {
        return 'fos_user_registration';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_publicbundle_contactusmessage';
    }
}
