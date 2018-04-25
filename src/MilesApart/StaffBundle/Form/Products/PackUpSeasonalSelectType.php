<?php

namespace MilesApart\StaffBundle\Form\Products;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PackUpSeasonalSelectType extends AbstractType
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
                    'class'=> 'col-md-4 col-xs-12 form-control'),
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

           ->add('business_premises', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 col-xs-12 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Business Premises',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:BusinessPremises',
                'property' => 'business_premises_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('business_premises')
                            ->where('business_premises.business_premises_type = :business_premises_type')
                            ->orderBy('business_premises.business_premises_name', 'ASC')
                            ->setParameter('business_premises_type', 1);
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
            'data_class' => 'MilesApart\StaffBundle\Entity\Products\PackUpSeasonal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_packupseasonalselect';
    }
}
