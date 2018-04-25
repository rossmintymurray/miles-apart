<?php

namespace MilesApart\StaffBundle\Form\Business;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class StockLocationShelfEntryType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
            $builder
            ->add('stock_location_shelf_x_start', null, array(
                'attr' => array(
                    'class'=> 'col-md-1 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Shelf X Start',
                'required'  => true,
            ));

            $builder
            ->add('stock_location_shelf_y_start', null, array(
                'attr' => array(
                    'class'=> 'col-md-1 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Shelf Y Start',
                'required'  => true,
            ));
            $builder
            ->add('stock_location_shelf_x_end', null, array(
                'attr' => array(
                    'class'=> 'col-md-1 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Shelf X End',
                'required'  => true,
            ));

            $builder
            ->add('stock_location_shelf_y_end', null, array(
                'attr' => array(
                    'class'=> 'col-md-1 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Shelf Y End',
                'required'  => true,
            ));

            $builder->add('stock_location_shelf_wall', 'choice', array(
                'attr' => array(
                    'class'=> 'col-md-2 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Stock Location Shelf Wall',
                'choices'   => array(
                    'N'   => 'North',
                    'S'   => 'South',
                    'E'   => 'East',
                    'W'   => 'West',
                    'C'   => 'Central',
                    'CN'   => 'CentralNorth',
                    'CS'   => 'CentralSouth',
                    'CE'   => 'CentralEast',
                    'CW'   => 'CentralWest',
                ),
                'required'  => true,
            ));

        $builder
            ->add('stock_location', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Stock Location',
                'required'  => true,
                'class' => 'MilesApartAdminBundle:StockLocation',
                'property' => 'stock_location_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('stock_location')
                                  ->orderBy('stock_location.stock_location_name', 'ASC');
                    },
            ));

    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\StaffBundle\Entity\Business\StockLocationShelfEntry'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_stocklocationshelf';
    }
}
