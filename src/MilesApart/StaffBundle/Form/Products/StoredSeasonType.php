<?php

namespace MilesApart\StaffBundle\Form\Products;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class StoredSeasonType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //Add the season
           ->add('season', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Season',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:Season',
                'property' => 'season_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('season')
                                  ->orderBy('season.season_name', 'ASC');
                    },
            ))


            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\SeasonalStorageBox'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_storedseason';
    }
}
