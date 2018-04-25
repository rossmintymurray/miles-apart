<?php

namespace MilesApart\StaffBundle\Form\Finances;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class DailyTakeBusinessPremisesPettyCashType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('petty_cash_value', null, array(
                'attr' => array(
                    'class'=> 'col-md-3 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Petty cash value',
                'required'  => true,
            ))

        ->add('expenses_type', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Expense type',
                'required'  => true,
                'empty_value' => 'Select one...',
                'class' => 'MilesApartAdminBundle:ExpensesType',
                'property' => 'expenses_type_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('expenses_type')
                                  ->orderBy('expenses_type.expenses_type_name', 'ASC');
                    },
            ))

        ->add('expenses_company', 'entity', array(
                'attr' => array(
                    'class'=> 'col-md-4 form-control'),
                'label_attr'=> array('class'=>'col-md-4 control-label'),
                'label'=>'Company',
                'required'  => false,
                 'empty_value' => 'Select one...',
                'class' => 'MilesApartAdminBundle:ExpensesCompany',
                'property' => 'expenses_company_name', 
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('expenses_company')
                                  ->orderBy('expenses_company.expenses_company_name', 'ASC');
                    },
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
            'data_class' => 'MilesApart\AdminBundle\Entity\DailyTakeBusinessPremisesPettyCash'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_staffbundle_dailytakebusinesspremisespettycash';
    }
}