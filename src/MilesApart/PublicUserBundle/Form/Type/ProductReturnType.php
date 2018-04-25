<?php
namespace MilesApart\PublicUserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProductReturnType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);

        $builder
            ->add('returned_reason', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'inline right'),
                'label'=>'Returned reason',
                'required'  => true,
                'empty_value' => 'Select one...',
                'class' => 'MilesApartAdminBundle:ReturnedReason',
                'property' => 'returned_reason',
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('returned_reason')
                                  ->orderBy('returned_reason.returned_reason', 'ASC');
                    },
            ));

        $builder
            ->add('returned_product_quantity', "integer", array(
                'attr' => array('min' => '1', 'max' => $options['max']),
                'label_attr'=> array('class'=>'inline right'),
                'label'=>'Returned quantity',
                'required'  => false,
            ));   

        $builder
            ->add('return_notes', "textarea", array(
                'attr' => array(
                    'pattern' => '[a-zA-Z]+'),
                'label_attr'=> array('class'=>'inline right'),
                'label'=>'Notes',
                'required'  => false,
            ));   
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\ReturnedProduct'
        ));

        $resolver->setRequired(array(
            'max'
        ));

    }

    public function getName()
    {
        return 'milesapart_publicuser_returnedproduct';
    }
}    


