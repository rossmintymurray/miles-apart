<?php
namespace MilesApart\PublicUserBundle\Form\Type;

use MilesApart\PublicUserBundle\Form\Type\CustomerRegistrationFormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;


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

        //Ceck if business or personal customer and remove the other form element
        /*$builder->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();
            ladybug_dump($form->get('is_customer_business')->getData());
            if ($form->get('is_customer_business')->getData() === false) {
                $form->get('business_customer_representative')->setData(null);
            } else if ($form->get('is_customer_business')->getData() === true) {
                $event->getForm()->remove('personal_customer');
            }

        });*/

        $formModifier = function (FormInterface $form,  $is_customer_business = null) {
            if ($is_customer_business === null || $is_customer_business === true) {
                $form->add('business_customer_representative', new BusinessCustomerRepresentativeRegistrationFormType(), array(
                    'data_class' => 'MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative',
                    'required' => true,
                    'mapped' => true,
                ));
            }

            if ($is_customer_business === null || $is_customer_business === false) {
                $form->add('personal_customer', new PersonalCustomerRegistrationFormType(), array(
                    'data_class' => 'MilesApart\AdminBundle\Entity\PersonalCustomer',
                    'required' => true,
                    'mapped' => true,
                ));
            }
        };


        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                if($data) {
                    $formModifier($event->getForm(), $data->getIsCustomerBusiness());
                }
            }
        );

        $builder->get('is_customer_business')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $data = $event->getForm()->getData();
                ladybug_dump($data);
                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), true);
            }
        );


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


