<?php
namespace MilesApart\BasketBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class PostOrderCreateAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);
        
       $builder->remove('username');  // we use email as the username

    }

    public function getParent()
    {
       return 'fos_user_registration';
    }


    public function getName()
    {
        return 'milesapart_publicuser_postorderregistration';
    }
}    



