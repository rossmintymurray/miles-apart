<?php

namespace MilesApart\StaffBundle\Form\Finances;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManager;

class DailyTakeBusinessPremisesType extends AbstractType
{
    private $daily_take_date;
    private $daily_take_date_date;
    private $em;
    private $nots;

    public function __construct($daily_take_date,  EntityManager $em, $edit)
    {
        $this->daily_take_date = $daily_take_date;
        if ($edit == false) {
            $this->em = $em;
            

            $this->nots = '4';
            $notsstring = "";
            
            $qb = $em->createQueryBuilder();

            $nots = $qb->select('IDENTITY(dtbp.business_premises) AS id')
                      ->from('MilesApartAdminBundle:DailyTakeBusinessPremises', 'dtbp')
                      ->leftJoin('dtbp.business_premises', 'bp')
                      ->leftJoin('dtbp.daily_take', 'dt')
                      ->where('dt.id = :daily_take_date')
                      ->setParameter('daily_take_date', $daily_take_date)
                      ->getQuery()
                      ->getResult();

            $count = count($nots);
            if ($count > 0) {
                $i = 1;
                foreach ($nots as $key => $value) {
                    if ($i < $count) {
                        $notsstring .= $nots[$key]['id'] . ", ";
                    } else {
                        $notsstring .= $nots[$key]['id'];
                    }
                    $i++;
                }
            } else {
                $notsstring = 0;
            }

            
            $this->nots = $notsstring;

            $this->edit = false;
        } else {
            $this->edit = true;
        }

        //Get the daily take date.

        if($this->daily_take_date) {
            $daily_take_date = $em->getRepository('MilesApartAdminBundle:DailyTake')->findOneById($this->daily_take_date);
            $this->daily_take_date_date = $daily_take_date->getDailyTakeDate();


        }

       
        
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $edit = $this->edit;
        if ($edit == false) {
            $nots = $this->nots; 
            
            $builder
            ->add('business_premises', 'entity', array(
                    'attr' => array(
                        'class'=> 'col-md-3 col-sm-3 form-control'),
                    'label_attr'=> array('class'=>'col-md-4 col-sm-4 control-label'),
                    'label'=> 'Business premises',
                    'required'  => true,
                    'class' => 'MilesApartAdminBundle:BusinessPremises',
                    'property' => 'business_premises_name', 
                    'query_builder' => function(EntityRepository $em) use ($nots) {
                        $qb = $em->createQueryBuilder('bprem');
                        $qb->select('bp')
                                     ->from('MilesApartAdminBundle:BusinessPremises', 'bp')
                                     ->where($qb->expr()->notIn('bp.id', $nots))
                                     ->andWhere('bp.business_premises_type = :businessTypes' )
                                     ->setParameter('businessTypes', '1 | 3');
                            

                        return $qb;
                        }
                ));
        } else {
        $builder
            ->add('business_premises', 'entity', array(
                    'attr' => array(
                        'class'=> 'col-md-3 col-sm-3 form-control'),
                    'label_attr'=> array('class'=>'col-md-4  col-sm-4 control-label'),
                    'label'=> 'Business premises',
                    'required'  => true,
                    'class' => 'MilesApartAdminBundle:BusinessPremises',
                    'property' => 'business_premises_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('business_premises')
                                  ->orderBy('business_premises.business_premises_name', 'ASC');
                    }
                ));
        }
        $builder
        ->add('z_reading_cash', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 col-sm-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-sm-4 control-label'),
                'label'=>'Z cash reading',
                'required'  => false,
            ))

        ->add('z_reading_card', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 col-sm-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-sm-4 control-label'),
                'label'=>'Z card reading',
                'required'  => false,
            ))

        ->add('transactions', 'integer', array(
                'attr' => array(
                    'class'=> 'col-md-2 col-sm-2 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-sm-4 control-label'),
                'label'=>'Transactions',
                'required'  => false,
            ))

        ->add('counted_cash', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 col-sm-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-sm-4 control-label'),
                'label'=>'Counted cash',
                'required'  => false,
            ))

        ->add('counted_card', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 col-sm-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 col-sm-4 control-label'),
                'label'=>'Banking card',
                'required'  => false,
            ))

        ->add('daily_take_business_premises_petty_cash', 'collection', array(
                
                'type'          => new DailyTakeBusinessPremisesPettyCashType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'petty__cash__name__',
                'options'       => array(),
                'label'         => false,

                
                
            ))

        ->add('daily_take_business_premises_shop_department', 'collection', array(
                
                'type'          => new DailyTakeBusinessPremisesShopDepartmentType(),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'shop__department__name__',
                'options'       => array(),
                'label'         => false,
                
                
            ))

        ->add('employee_payment', 'collection', array(
                
                'type'          => new EmployeePaymentType($this->daily_take_date_date),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'employee__payment__name__',
                'options'       => array(),
                'label'         => false,
                
                
            ))

        ->add('employee_statutory_payment', 'collection', array(
                
                'type'          => new EmployeeStatutoryPaymentType($this->daily_take_date_date),
                'allow_add'     => true,
                'allow_delete'  => true,
                'prototype'     => true,
                'prototype_name' => 'employee__statutory__payment__name__',
                'options'       => array(),
                'label'         => false,
                
                
            ))

            ;
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\DailyTakeBusinessPremises'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_dailytakebusinesspremises';
    }
}