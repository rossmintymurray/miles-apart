<?php

namespace MilesApart\StaffBundle\Form\Products\EditProduct;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class EditProductKeywordType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    

        

            $builder
            ->add('keyword_word', null, array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'New keyword',
                'required'  => false,
            ));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\Keyword'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_adminbundle_productkeyword';
    }
}
