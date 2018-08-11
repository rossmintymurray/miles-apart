<?php

namespace MilesApart\SellerBundle\Service;
use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;

use MilesApart\AdminBundle\Entity\CustomerOrder;
use MilesApart\AdminBundle\Entity\RoyalMailShipment;
use MilesApart\AdminBundle\Entity\ShippingManifest;

use MilesApart\AdminBundle\Entity\Customer;
use MilesApart\AdminBundle\Entity\BusinessCustomer;
use MilesApart\AdminBundle\Entity\BusinessCustomerRepresentative;
use MilesApart\AdminBundle\Entity\PersonalCustomer;
use MilesApart\AdminBundle\Entity\CustomerAddress;
use MilesApart\AdminBundle\Entity\CustomerOrderProduct;
use MilesApart\SellerBundle\Entity\AmazonOrder;

class AmazonService
{
    /**
     *
     * @var EntityManager
     */
    protected $em;
    private $logger;

    private $mwsClientPoolUk;
    private $shipment;
    private $manifest;



    public function __construct(EntityManager $entityManager, LoggerInterface $logger, $mwsClientPoolUk)
    {
        $this->em = $entityManager;
        $this->logger = $logger;

        $this->mwsClientPoolUk = $mwsClientPoolUk;

    }


    /*************************************************
     * Get oustanding amazon orders (returns a log entry to indicate success or failure and how many orders)
     *************************************************/
    public function getAmazonOrders()
    {
        //Call the function to get new orders
        $orders = $this->getNewOrders();

        //If there are orders
        if($orders != null) {

            //Check if there are any orders
            if(count($orders) > 0) {
                //Get the orders
                $orders_output = $orders->getListOrdersResult()->getOrders();

                //Save the orders
                $output = $this->saveNewOrders($orders);

                //If saved
                if ($output != null) {
                    //Get the items on the order
                    $output_output = $output->getListOrderItemsResult()->getOrderItems();
                } else {
                    $output_output = null;
                }
            } else {
                $orders_output = null;
                $output_output = null;
            }
        } else {
            $orders_output = null;
            $output_output = null;
        }

        //Create log entry
        return array(
            'orders_output' => $orders_output,
            'output_output' => $output_output,
        );
    }

    public function getNewOrders()
    {
        //Get the Amazon setup
        $mwsClientPoolUk = $this->mwsClientPoolUk;
        $mwsOrdersClientPack = $mwsClientPoolUk->getOrderClientPack();

        $em = $this->em;

        //Get most recent amazon order from the MA database
        $amazon_order = $em->getRepository('MilesApartAdminBundle:CustomerOrder')->findLatestAmazonOrder();

        //If there are no amazon orders yet.
        if($amazon_order == null) {

            //Set start date to arbitary date in the past
            $start_date = new \DateTime("now", new \DateTimeZone("Europe/London"));
            $start_date->setDate(2001, 1, 1);
        } else {
            //Set the start date as the date the last order was imported
            $start_date = $amazon_order[0]->getCustomerOrderDateCreated();

        }

        //Set the end date as today
        $end_date = new \DateTime("now");
        //Remove 2 minutes
        $end_date->modify('+57 minutes');


        $mwsResponse = $mwsOrdersClientPack->callListOrdersByCreateDate($start_date, $end_date);

        return $mwsResponse;
    }

    public function getNewOrderContents($amazon_order_id)
    {
        //Get the Amazon setup
        $mwsClientPoolUk = $this->container->get('caponica_mws_client_pool_uk');
        $mwsOrdersClientPack = $mwsClientPoolUk->getOrderClientPack();

        $mwsResponse = $mwsOrdersClientPack->callListOrderItems($amazon_order_id);

        return $mwsResponse;
    }

    //Map new orders and save to MA database
    public function saveNewOrders($orders)
    {
        ladybug_dump($orders->getListOrdersResult()->getOrders());
        $em = $this->em;

        //Iterate over the orders
        foreach($orders->getListOrdersResult()->getOrders() as $order) {
            ladybug_dump($order->getIsBusinessOrder());
            //Check if the order exists in the MA DB
            $existing_amazon_order = $em->getRepository('MilesApartSellerBundle:AmazonOrder')->findOneBy(array('amazon_order_id' => $order->getAmazonOrderId()));

            if($existing_amazon_order != null) {
                //Update the order
                ladybug_dump("notexisting");
            } else {



                //Split name into first name and surname
                $arr = explode(' ', trim($order->getBuyerName()));
                echo $arr[0]; // will print Test
                $amazon_first_name = ucfirst($arr[0]);
                $amazon_surname = "";
                foreach($arr as $key => $name) {
                    if($key >= 1) {
                        $amazon_surname .= ucfirst($name);
                    }
                }

                //Create new customer  object
                $customer = new Customer();

                //Set up the variables


                //Create new personal or business customer object
                if($order->getIsBusinessOrder() != true){
                    ladybug_dump("businesss");
                    $business_customer = new BusinessCustomer();

                    $business_customer_representative = new BusinessCustomerRepresentative();

                    //Set up the variables
                    $business_customer_representative->setBusinessCustomerRepresentativeFirstName($amazon_first_name);
                    $business_customer_representative->setBusinessCustomerRepresentativeSurname($amazon_surname);
                    $business_customer->addBusinessCustomerRepresentative($business_customer_representative);
                    $customer->setBusinessCustomer($business_customer);
                } else {
                    ladybug_dump("personal");
                    $personal_customer = new PersonalCustomer();

                    //Set up the variables
                    $personal_customer->setCustomer($customer);
                    $personal_customer->setPersonalCustomerFirstName($amazon_first_name);
                    $personal_customer->setPersonalCustomerSurname($amazon_surname);
                    $personal_customer->setPersonalCustomerEmailAddress($order->getBuyerEmail());
                    $customer->setPersonalCustomer($personal_customer);

                }

                //Create new customer address object
                $customer_address = new CustomerAddress();

                //Set up the variables
                $customer_address->setCustomer($customer);
                $customer_address->setCustomerAddressContactFirstName($amazon_first_name);
                $customer_address->setCustomerAddressContactSurname($amazon_surname);
                $customer_address->setCustomerAddressLine1($order->getShippingAddress()->getAddressLine1());
                $customer_address->setCustomerAddressLine2($order->getShippingAddress()->getAddressLine2());
                $customer_address->setCustomerAddressTown($order->getShippingAddress()->getCity());
                $customer_address->setCustomerAddressCounty($order->getShippingAddress()->getStateOrRegion());
                $customer_address->setCustomerAddressPostcode($order->getShippingAddress()->getPostalCode());
                $customer_address->setCustomerAddressCountry($order->getShippingAddress()->getCountryCode());

                $customer->addCustomerAddress($customer_address);

                //Create new customer order object
                $customer_order = new CustomerOrder();

                //Set up the variables
                $customer_order->setCustomer($customer);
                $customer_order->setDeliveryAddress($customer_address);
                $customer_order->setCustomerOrderTotalPricePaid($order->getOrderTotal()->getAmount());
                $customer_order->setCustomerOrderSource(
                    $em->getRepository('MilesApartAdminBundle:CustomerOrderSource')->findOneBy(
                        array( 'id' => 2 )
                    ));

                //Check the order status and insert into the DB as appropriate
                if($order->getOrderStatus() == "Pending") {
                    $customer_order->setCustomerOrderState(
                        $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                            array( 'id' => 1 )
                        ));
                } else if($order->getOrderStatus() == "Unshipped") {
                    $customer_order->setCustomerOrderState(
                        $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                            array( 'id' => 2 )
                        ));
                } else if($order->getOrderStatus() == "Shipped") {
                    $customer_order->setCustomerOrderState(
                        $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                            array( 'id' => 6 )
                        ));
                } else if($order->getOrderStatus() == "Cancelled") {
                    $customer_order->setCustomerOrderState(
                        $em->getRepository('MilesApartAdminBundle:CustomerOrderState')->findOneBy(
                            array( 'id' => 9)
                        ));
                }

                $customer->addCustomerOrder($customer_order);

                //Get the order contents
                $order_contents = $this->getNewOrderContents($order->getAmazonOrderId());

                //Set delivery total variable.
                $amazon_delivery_total_price = 0.00;

                //For each product in the order
                foreach($order_contents->getListOrderItemsResult()->getOrderItems() as $order_product) {

                    //Get the product from the DB
                    $product = $em->getRepository('MilesApartAdminBundle:Product')->findOneById($order_product->getSellerSKU());

                    //Create new customer order object
                    $customer_order_product = new CustomerOrderProduct();

                    //Set up the variables
                    $customer_order_product->setCustomerOrder($customer_order);
                    $customer_order_product->setProduct($product);
                    $customer_order_product->setCustomerOrderProductQuantity($order_product->getQuantityOrdered());

                    $customer_order->addCustomerOrderProduct($customer_order_product);

                    //Update the delivery total
                    $amazon_delivery_total_price = $amazon_delivery_total_price + $order_product->getShippingPrice()->getAmount();
                }


                //Update the total shipping price on customer order
                $customer_order->setCustomerOrderShippingPaid($amazon_delivery_total_price);

                //Create amazon order object
                $amazon_order = new AmazonOrder();

                //Set up the variables
                $amazon_order->setAmazonOrderId($order->getAmazonOrderId());
                $amazon_order->setPurchaseDate(new \DateTime($order->getPurchaseDate(), new \DateTimeZone('Europe/London')));
                $amazon_order->setLastUpdateDate(new \DateTime($order->getLastUpdateDate(), new \DateTimeZone('Europe/London')));
                $amazon_order->setMarketplaceId($order->getMarketplaceId());
                $amazon_order->setCustomerOrder($customer_order);

                $customer_order->setAmazonOrder($amazon_order);

                ladybug_dump($customer_order->getCustomerOrderLargestWidth());
                ladybug_dump($customer_order->getCustomerOrderLargestHeight());
                ladybug_dump($customer_order->getCustomerOrderLargestDepth());
                ladybug_dump($customer_order->getCustomerOrderTotalWeight());
                //Set up the postage band details
                //Query the model with the order, height width, depth and weight
                $postage_band = $em->getRepository('MilesApartAdminBundle:PostageBand')->findPostageBandBySizes($customer_order->getCustomerOrderLargestWidth(), $customer_order->getCustomerOrderLargestHeight(), $customer_order->getCustomerOrderLargestDepth(), $customer_order->getCustomerOrderTotalWeight());

                ladybug_dump($postage_band);

                $customer_order->setDeliveryOption(
                    $em->getRepository('MilesApartAdminBundle:PostageBandDispatchLogistics')->findOneBy(array('postage_band' => $postage_band[0]->getId(), 'postage_type' => 2))
                );

                ladybug_dump($customer_order);

                //Persist the order and return success or failure
                $em->persist($customer_order);
                $em->flush();

            }
            return $order_contents;

        }
    }
}
