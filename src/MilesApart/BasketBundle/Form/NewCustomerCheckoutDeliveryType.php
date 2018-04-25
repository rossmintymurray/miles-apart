<?php

namespace MilesApart\BasketBundle\Form;

use MilesApart\BasketBundle\Form\NewCustomerDeliveryAddressType;
use MilesApart\BasketBundle\Form\NewCustomerCustomerType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
class NewCustomerCheckoutDeliveryType extends AbstractType
{
    private $delivery_options;

    private $em;

    public function __construct($delivery_options, $em)
    {
        $this->delivery_options = $delivery_options;
        $this->em = $em;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

         $builder->add('customer',new NewCustomerCustomerType(),array(

                'data_class' => 'MilesApart\AdminBundle\Entity\Customer',
                'required' =>true,

            )
        );

       $builder->add('delivery_address',new NewCustomerDeliveryAddressType(),array(

                            'data_class' => 'MilesApart\AdminBundle\Entity\CustomerAddress',
                            'required' =>true,

                    )
                );

       $builder->add('billing_address',new NewCustomerBillingAddressType(),array(
                        'data_class' => 'MilesApart\AdminBundle\Entity\CustomerAddress',
                        'required' =>true,
                        'mapped' => false,
                    ));

       

       /* $builder->addEventListener(
            FormEvents::PRE_SET_DATA, 
            function (FormEvent $event) {
                // ... adding the name field if needed
                $form = $event->getForm();
                $data = $event->getData();

                $delivery_address = $data->getDeliveryAddress();

                
                
                
            }
        );
       
       $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                //ladybug_dump($data->getDeliveryAddress());
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $delivery_address = $data->getDeliveryAddress()->getCustomerAddressIsBilling();

                //$delivery_address = $data->getDeliveryAddress();

                if($delivery_address == true) {
                    $form->remove('billing_address');
                    
                }
            }
        );
*/
       

       /* $builder
            ->add('delivery_option', 'entity', array(
                'attr' => array(
                    'type'=> 'text',
                    'class' => ''),
                'label_attr'=> array('class'=>'right'),
                'label'=>'Delivery option',
                'class' => 'MilesApartAdminBundle:PostageBandDispatchLogistics',
                'required'  => true,
                'multiple'  => false,
                'expanded'  => true,
                'property' => 'postage_band_price', 
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('pbdl')
                        ->where('pbdl.postage_band = '. $this->delivery_options['postage_band_id'])
                        ->orderBy('pbdl.postage_band_price', 'ASC');
                },
                
            ));
*/
           
        //Check if the order is over £30
       
        $builder->add('delivery_option', 'choice', array(
            'choices'   => $this->fillDeliveryOptions(),
            'multiple'  => false,
            'expanded' => true,
            'label_attr'=> array('class'=>'right'),
            'label'=>'Delivery option',
            'mapped'=>false,
        ));
          
    }

    private function fillDeliveryOptions() {

    $er = $this->em->getRepository('MilesApartAdminBundle:PostageBandDispatchLogistics');
    $results = $er->createQueryBuilder('pbdl')
                ->where('pbdl.postage_band = '. $this->delivery_options['postage_band_id'])
                ->orderBy('pbdl.postage_band_price', 'ASC')
                ->getQuery()
               ->getResult()
               ;

    $delivery_options = array();
    foreach($results as $pbdl){

        //Get second class
        if($pbdl->getPostageType()->getId() == 2) {
            $second_class_delivery_option = array("id" => $pbdl->getId(), "price" => $pbdl->getPostageBandPrice());
        
        //Get first class
        } else if($pbdl->getPostageType()->getId() == 1) {
             $first_class_delivery_option = array("id" => $pbdl->getId(), "price" => $pbdl->getPostageBandPrice());
        
        }
    }
        
    //Check if the order is over £30
    if($this->delivery_options['second_class_postage'] == "Free") {

        //Remove second class price from first
        $new_first_class_price = "£" . number_format($first_class_delivery_option["price"] - $second_class_delivery_option["price"], 2, '.', ' ');
        
        //Reset first class
        $first_class_delivery_option = array("id" => $first_class_delivery_option["id"], "price" => $new_first_class_price);

        //Set second class price to free
        $second_class_delivery_option = array("id" => $second_class_delivery_option["id"], "price" => "Free");

    } else {
        //Add £ sign
        //Reset first class
        $first_class_delivery_option = array("id" => $first_class_delivery_option["id"], "price" => "£".$first_class_delivery_option["price"]);

        //Set second class price to free
        $second_class_delivery_option = array("id" => $second_class_delivery_option["id"], "price" => "£".$second_class_delivery_option["price"]);
    }

    //Push each one to the array
    array_push($delivery_options, array($second_class_delivery_option["id"]=>$second_class_delivery_option["price"]));
    array_push($delivery_options, array($first_class_delivery_option["id"]=>$first_class_delivery_option["price"])); // and so on..


    return $delivery_options;
}
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MilesApart\AdminBundle\Entity\CustomerOrder'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'milesapart_basketbundle_checkoutdelivery';
    }
}