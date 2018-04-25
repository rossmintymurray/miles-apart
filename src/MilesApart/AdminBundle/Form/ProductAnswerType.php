<?php

namespace MilesApart\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProductAnswerType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
            ->add('product_answer_text', 'textarea', array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Answer text',
            ));

         $builder
            ->add('product_question', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'class' => 'MilesApartAdminBundle:ProductQuestion',
                    'property' => 'product_question_text', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('product_question')
                                  ->orderBy('product_question.product_question_asked_date', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Question',
            ));

        $builder
            ->add('admin_user', 'entity', array(
            'attr' => array(
                'class'=> 'col-md-7 form-control'),
            'class' => 'MilesApartAdminBundle:AdminUser',
                    'property' => 'admin_user_username', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('admin_user')
                                  ->orderBy('admin_user.admin_user_username', 'ASC');
                    },
            'label_attr'=> array('class'=>'col-md-4 control-label'),
            'label'=>'Admin user',
            ));

      
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\ProductAnswer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_productanswer';
    }
}
