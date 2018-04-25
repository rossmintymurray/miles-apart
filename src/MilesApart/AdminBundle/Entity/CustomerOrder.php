<?php
// src/MilesApart/AdminBundle/Entity/CustomerOrder.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerOrderRepository")
 * @ORM\Table(name="customer_order")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerOrder
{
    //Define the values

    /**
     *  
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $customer_order_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $customer_order_date_modified;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $customer_order_shipping_paid;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=true)
     */
    protected $customer_order_total_price_paid;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customer_order", cascade={"persist"})
     * @ORM\JoinTable(name="customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerAddress", inversedBy="customer_order_delivery", cascade={"persist"})
     * @ORM\JoinTable(name="customer_address")
     * @ORM\JoinColumn(name="delivery_address_id", referencedColumnName="id")
     */
    protected $delivery_address;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerAddress", inversedBy="customer_order_billing", cascade={"persist"})
     * @ORM\JoinTable(name="customer_address")
     * @ORM\JoinColumn(name="billing_address_id", referencedColumnName="id")
     */
    protected $billing_address;

    /**
     * @ORM\OneToMany(targetEntity="CustomerOrderProduct", mappedBy="customer_order", cascade={"persist"})
     */
    protected $customer_order_product;

    /**
     * @ORM\OneToMany(targetEntity="RoyalMailShipment", mappedBy="customer_order")
     */
    protected $royal_mail_shipment;

    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=true)
     */
    protected $session_id;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerOrderState", inversedBy="customer_order")
     * @ORM\JoinTable(name="customer_order_state")
     * @ORM\JoinColumn(name="customer_order_state_id", referencedColumnName="id")
     */
    protected $customer_order_state;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerOrderSource", inversedBy="customer_order")
     * @ORM\JoinTable(name="customer_order_source")
     * @ORM\JoinColumn(name="customer_order_source_id", referencedColumnName="id")
     */
    protected $customer_order_source;
    
    /**
     * @ORM\ManyToOne(targetEntity="PostageBandDispatchLogistics", inversedBy="customer_order", cascade={"persist"})
     * @ORM\JoinTable(name="postage_band_dispatch_logistics")
     * @ORM\JoinColumn(name="postage_band_dispatch_logistics_id", referencedColumnName="id")
     */
    protected $delivery_option;

    /**
     * @ORM\OneToOne(targetEntity="BusinessCustomerRepresentativeCustomerOrder", mappedBy="customer_order", cascade={"persist"})
     */
    protected $business_customer_representative_customer_order;

    /**
     * @ORM\OneToOne(targetEntity="MilesApart\SellerBundle\Entity\AmazonOrder", mappedBy="customer_order", cascade={"persist"})
     */
    protected $amazon_order;

   
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setCustomerOrderDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCustomerOrderDateCreated() == null)
        {
            $this->setCustomerOrderDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customer_order_product = new \Doctrine\Common\Collections\ArrayCollection();

    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customer_order_date_created
     *
     * @param \DateTime $customerOrderDateCreated
     * @return CustomerOrder
     */
    public function setCustomerOrderDateCreated($customerOrderDateCreated)
    {
        $this->customer_order_date_created = $customerOrderDateCreated;
    
        return $this;
    }

    /**
     * Get customer_order_date_created
     *
     * @return \DateTime 
     */
    public function getCustomerOrderDateCreated()
    {
        return $this->customer_order_date_created;
    }

    /**
     * Set customer_order_date_modified
     *
     * @param \DateTime $customerOrderDateModified
     * @return CustomerOrder
     */
    public function setCustomerOrderDateModified($customerOrderDateModified)
    {
        $this->customer_order_date_modified = $customerOrderDateModified;
    
        return $this;
    }

    /**
     * Get customer_order_date_modified
     *
     * @return \DateTime 
     */
    public function getCustomerOrderDateModified()
    {
        return $this->customer_order_date_modified;
    }

    /**
     * Set session_id
     *
     * @param string $sessionId
     * @return CustomerOrder
     */
    public function setSessionId($sessionId)
    {
        $this->session_id = $sessionId;
    
        return $this;
    }

    /**
     * Get session_id
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->session_id;
    }

    /**
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return CustomerOrder
     */
    public function setCustomer(\MilesApart\AdminBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;
    
        return $this;
    }

    /**
     * Get customer
     *
     * @return \MilesApart\AdminBundle\Entity\Customer 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set delivery_address
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerAddress $deliveryAddress
     * @return CustomerOrder
     */
    public function setDeliveryAddress(\MilesApart\AdminBundle\Entity\CustomerAddress $deliveryAddress = null)
    {
        $this->delivery_address = $deliveryAddress;
    
        return $this;
    }

    /**
     * Get delivery_address
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerAddress 
     */
    public function getDeliveryAddress()
    {
        return $this->delivery_address;
    }

    /**
     * Set billing_address
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerAddress $billingAddress
     * @return CustomerOrder
     */
    public function setBillingAddress(\MilesApart\AdminBundle\Entity\CustomerAddress $billingAddress = null)
    {
        $this->billing_address = $billingAddress;
    
        return $this;
    }

    /**
     * Get billing_address
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerAddress 
     */
    public function getBillingAddress()
    {
        return $this->billing_address;
    }

    /**
     * Add customer_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct
     * @return CustomerOrder
     */
    public function addCustomerOrderProduct(\MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct)
    {
        $this->customer_order_product[] = $customerOrderProduct;
    
        return $this;
    }

    /**
     * Remove customer_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct
     */
    public function removeCustomerOrderProduct(\MilesApart\AdminBundle\Entity\CustomerOrderProduct $customerOrderProduct)
    {
        $this->customer_order_product->removeElement($customerOrderProduct);
    }

    /**
     * Get customer_order_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOrderProduct()
    {
        return $this->customer_order_product;
    }



    /**
     * Get total number of items in the order
     *
     * 
     */
    public function getOrderItemQty()
    {
        $order_item_qty = 0;
        
        //If there are product in the order
        if (count($this->getCustomerOrderProduct()) > 0) {

            //For each product
            foreach($this->getCustomerOrderProduct() as $key => $value) {
                
                //Add the number of items to the total
                $order_item_qty =  $order_item_qty + $value->getCustomerOrderProductQuantity();
            }
        } 

        return $order_item_qty;
    }

    /**
     * Get number of products remaining to be picked
     *
     * 
     */
    public function getOrderRemainingPick()
    {
        $order_remaining_pick = 0;
        
        //If there are product in the order
        if (count($this->getCustomerOrderProduct()) > 0) {

            //For each product
            foreach($this->getCustomerOrderProduct() as $key => $value) {
                
                //Check if the product has not yet been picked 
                if($value->getCustomerOrderProductState() == NULL) {
                    //Add the number of items to the total
                    $order_remaining_pick++; 
                }
            }
        } 

        return $order_remaining_pick;
    }

    /**
     * Get number of products remaining to be packed
     *
     * 
     */
    public function getOrderRemainingPack()
    {
        $order_remaining_pack = 0;
        
        //If there are product in the order
        if (count($this->getCustomerOrderProduct()) > 0) {

            //For each product
            foreach($this->getCustomerOrderProduct() as $key => $value) {
                
                //Check if the product has not yet been picked
                if($value->getCustomerOrderProductState()){
                    if($value->getCustomerOrderProductState()->getId() == 1) {
                        //Add the number of items to the total
                        $order_remaining_pack++; 
                    }
                }
            }
        } 

        return $order_remaining_pack;
    }



    /**
     * Set customer_order_state
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderState $customerOrderState
     * @return CustomerOrder
     */
    public function setCustomerOrderState(\MilesApart\AdminBundle\Entity\CustomerOrderState $customerOrderState = null)
    {
        $this->customer_order_state = $customerOrderState;
    
        return $this;
    }

    /**
     * Get customer_order_state
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerOrderState 
     */
    public function getCustomerOrderState()
    {
        return $this->customer_order_state;
    }

    /**
     * Get customer_order_total_weight
     *
     *  
     */
    public function getCustomerOrderTotalWeight()
    {
        $total_weight = 0;
        //For each product
        foreach($this->getCustomerOrderProduct() as $key => $value) {
            
            //Add the weight of each item to the total
            $total_weight = $total_weight + $value->getProduct()->getProductWeight();
        }

        return $total_weight;
    }

    /**
     * Set royal_mail_shipment
     *
     * @param \MilesApart\AdminBundle\Entity\RoyalMailShipment $royalMailShipment
     * @return CustomerOrder
     */
    public function setRoyalMailShipment(\MilesApart\AdminBundle\Entity\RoyalMailShipment $royalMailShipment = null)
    {
        $this->royal_mail_shipment = $royalMailShipment;

        return $this;
    }

    /**
     * Get royal_mail_shipment
     *
     * @return \MilesApart\AdminBundle\Entity\RoyalMailShipment 
     */
    public function getRoyalMailShipment()
    {
        return $this->royal_mail_shipment;
    }

    /**
     * Get largest width item
     *
     */
    public function getCustomerOrderLargestWidth()
    {
        //Get largest item
        //Set initial width
        $width = 0;
        
        //Set maximums
        foreach($this->getCustomerOrderProduct() as $customer_order_product){
            
            //For width
            if($customer_order_product->getProduct()->getProductWidth() > $width) {
                $width = $customer_order_product->getProduct()->getProductWidth();
            }
        }

        return $width;
    }

     /**
     * Get largest height item
     *
     */
    public function getCustomerOrderLargestHeight()
    {
        //Get largest item
        //Set initial height
        $height = 0;
        
        //Set maximums
        foreach($this->getCustomerOrderProduct() as $customer_order_product){
            
            //For height
            if($customer_order_product->getProduct()->getProductHeight() > $height) {
                $height = $customer_order_product->getProduct()->getProductHeight();
            }
        }

        return $height;
    }

     /**
     * Get largest depth item
     *
     */
    public function getCustomerOrderLargestDepth()
    {
        //Get largest item
        //Set initial depth
        $depth = 0;
        
        //Set maximums
        foreach($this->getCustomerOrderProduct() as $customer_order_product){
            
            //For depth
            if($customer_order_product->getProduct()->getProductDepth() > $depth) {
                $depth = $customer_order_product->getProduct()->getProductDepth();
            }
        }

        return $depth;    }

    /**
     * Set delivery_option
     *
     * @param \MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $deliveryOption
     * @return CustomerOrder
     */
    public function setDeliveryOption(\MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics $deliveryOption = null)
    {
        $this->delivery_option = $deliveryOption;

        return $this;
    }

    /**
     * Get delivery_option
     *
     * @return \MilesApart\AdminBundle\Entity\PostageBandDispatchLogistics 
     */
    public function getDeliveryOption()
    {
        return $this->delivery_option;
    }

    //Get order subtotal
     public function getCustomerOrderTotalPrice()
    {
        $customer_order_total_price = 0;
        //If there are items in the basket
        if(count($this->getCustomerOrderProduct()) > 0) {
            foreach($this->getCustomerOrderProduct() as $key => $value) {
                $customer_order_total_price = $customer_order_total_price + $value->getCustomerOrderProductTotalPrice();
            }
        }

        return $customer_order_total_price;
    }

     //Get basket total qty
    public function getCustomerOrderTotalPriceDisplay()
    {
       $formatted_price = number_format($this->getCustomerOrderTotalPrice(), 2, '.', ',');

        if ($formatted_price < 1 && $formatted_price > 0) {
            $formatted_price = $formatted_price * 100;
            $formatted_price = $formatted_price . "p";
        } else if ($formatted_price >= 1) {
            $formatted_price = "£" . $formatted_price;
        } else {
            $formatted_price = "N/A";
        }
        return $formatted_price;
    }

    //Get basket total qty
    public function getCustomerOrderShippingPaidDisplay()
    {
       $formatted_price = number_format($this->getCustomerOrderShippingPaid(), 2, '.', ',');

        if($formatted_price == 0) {
            $formatted_price = "Free";
        } else {
            $formatted_price = "£" . $formatted_price;
        }
       
       
        return $formatted_price;
    }

    //Get grand total
    public function getGrandTotal() {
        return $this->getCustomerOrderTotalPrice() + $this->getCustomerOrderShippingPaid();
    }

    //Get basket total qty
    public function getGrandTotalDisplay()
    {
       $formatted_price = number_format($this->getGrandTotal(), 2, '.', ',');

        if ($formatted_price < 1 && $formatted_price > 0) {
            $formatted_price = $formatted_price * 100;
            $formatted_price = $formatted_price . "p";
        } else if ($formatted_price >= 1) {
            $formatted_price = "£" . $formatted_price;
        } else {
            $formatted_price = "N/A";
        }
        return $formatted_price;
    }

    public function getCustomerOrderEmailAddress() 
    {
        if($this->getCustomer()->getPersonalCustomer() != null) {

            $email_address = $this->getCustomer()->getPersonalCustomer()->getPersonalCustomerEmailAddress();
        } else if($this->getBusinessCustomerRepresentativeCustomerOrder() != null) {
            if($this->getBusinessCustomerRepresentativeCustomerOrder()->getBusinessCustomerRepresentative() != null) {
                $email_address = $this->getBusinessCustomerRepresentativeCustomerOrder()->getBusinessCustomerRepresentative()->getBusinessCustomerRepresentativeEmailAddress();
            } else {
                $email_address = "rossmintymurray@icloud.com";
            }
        } else {
            $email_address = "rossmintymurray@icloud.com";
        }

        return $email_address;
    }

    public function getCustomerOrderFullName() 
    {
        if($this->getCustomer()->getPersonalCustomer() != null) {

            $name = $this->getCustomer()->getPersonalCustomer()->getPersonalCustomerFullName();
        } else if($this->getBusinessCustomerRepresentativeCustomerOrder() != null) {
            if($this->getBusinessCustomerRepresentativeCustomerOrder()->getBusinessCustomerRepresentative() != null) {
                $name = $this->getBusinessCustomerRepresentativeCustomerOrder()->getBusinessCustomerRepresentative()->getBusinessCustomerRepresentativeFullName();
            } 
        } else {
            $name = $this->getDeliveryAddress()->getCustomerAddressContactFullName();
        }

        return $name;
    }
    /**
     * Set customer_order_total_price_paid
     *
     * @param string $customerOrderTotalPricePaid
     * @return CustomerOrder
     */
    public function setCustomerOrderTotalPricePaid($customerOrderTotalPricePaid)
    {
        $this->customer_order_total_price_paid = $customerOrderTotalPricePaid;

        return $this;
    }

    /**
     * Get customer_order_total_price_paid
     *
     * @return string 
     */
    public function getCustomerOrderTotalPricePaid()
    {
        return $this->customer_order_total_price_paid;
    }

    /**
     * Set customer_order_shipping_paid
     *
     * @param string $customerOrderShippingPaid
     * @return CustomerOrder
     */
    public function setCustomerOrderShippingPaid($customerOrderShippingPaid)
    {
        $this->customer_order_shipping_paid = $customerOrderShippingPaid;

        return $this;
    }

    /**
     * Get customer_order_shipping_paid
     *
     * @return string 
     */
    public function getCustomerOrderShippingPaid()
    {
        return $this->customer_order_shipping_paid;
    }


    /**
     * Set business_customer_representative_customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessCustomerRepresentativeCustomerOrder $businessCustomerRepresentativeCustomerOrder
     * @return CustomerOrder
     */
    public function setBusinessCustomerRepresentativeCustomerOrder(\MilesApart\AdminBundle\Entity\BusinessCustomerRepresentativeCustomerOrder $businessCustomerRepresentativeCustomerOrder = null)
    {
        $this->business_customer_representative_customer_order = $businessCustomerRepresentativeCustomerOrder;

        return $this;
    }

    /**
     * Get business_customer_representative_customer_order
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessCustomerRepresentativeCustomerOrder 
     */
    public function getBusinessCustomerRepresentativeCustomerOrder()
    {
        return $this->business_customer_representative_customer_order;
    }

     /**
     * Set customer_order_source
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrderSource $customerOrderSource
     * @return CustomerOrder
     */
    public function setCustomerOrderSource(\MilesApart\AdminBundle\Entity\CustomerOrderSource $customerOrderSource = null)
    {
        $this->customer_order_source = $customerOrderSource;
    
        return $this;
    }

    /**
     * Get customer_order_source
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerOrderSource
     */
    public function getCustomerOrderSource()
    {
        return $this->customer_order_source;
    }

    /**
     * Set amazon_order
     *
     * @param \MilesApart\SellerBundle\Entity\AmazonOrder $amazonOrder
     * @return CustomerOrder
     */
    public function setAmazonOrder(\MilesApart\SellerBundle\Entity\AmazonOrder $amazonOrder = null)
    {
        $this->amazon_order = $amazonOrder;

        return $this;
    }

    /**
     * Get amazon_order
     *
     * @return \MilesApart\AdminBundle\Entity\AmazonOrder 
     */
    public function getAmazonOrder()
    {
        return $this->amazon_order;
    }

    /**********************************************
     * For calculating postage band for Amazon orders (MA orders are calculated in the basket)
     *
     **********************************************/
    /**
     * Get largest length item
     *
     */
    public function getCustomerOrderLargestLength()
    {
        //Get largest item
        //Set initial length
        $length = 0;
        
        //Set maximums
        foreach($this->getCustomerOrderProduct() as $customer_order_product){
            
            //For length
            if($customer_order_product->getProduct()->getProductHeight() > $length) {
                $length = $customer_order_product->getProduct()->getProductHeight();
            }
        }

        return $length;
    }

   

     

     /**********************************************
     *
     * For calculating costs of order (to calculate profit - and thus, bonus!!)
     *
     **********************************************/
     //First split the orders 
     public function getMAWebsiteOrderCost() 
     {
        //Set the running total
        $total_cost = 0.00;

        //First, postage costs - get the postage band dispaych logistics 
        $postage_cost = $this->getDeliveryOption()->getPostageBandPrice();

        //Add to the total
        $total_cost = $total_cost + $postage_cost;


        //Secondly, get the cost of the products sold
        foreach($this->getCustomerOrderProduct() as $value) {
            //Cost of goods
            if($value->getProduct()->getProductCostByDateDecimal($this->getCustomerOrderDateCreated()) != null) {
                $total_cost = $total_cost + $value->getProduct()->getProductCostByDateDecimal($this->getCustomerOrderDateCreated());
            }

            
        }


        //Thirdly, calculate VAT paid
        $ex_vat = round($this->getCustomerOrderTotalPricePaid() / 1.2, 2);
        $vat = $this->getCustomerOrderTotalPricePaid() - $ex_vat;
        $total_cost = $total_cost + $vat;

        //Forthly, calculate credit card fees
        $card_fees = round($this->getCustomerOrderTotalPricePaid() * (1.9/100));
        $total_cost = $total_cost + $card_fees + 0.20;

        //Return the total cost
        return $total_cost;

    }

    //Return the Amazon costs of an order
    public function getAmazonOrderCost()
    {
        //Set the running total
        $total_cost = 0.00;

        //First, postage costs - get the postage band dispaych logistics 
        $postage_cost = $this->getDeliveryOption()->getPostageBandPrice();

        //Add to the total
        $total_cost = $total_cost + $postage_cost;


        //Secondly, get the cost of the products sold
        foreach($this->getCustomerOrderProduct() as $value) {
            //Cost of goods
            if($value->getProduct()->getProductCostByDateDecimal($this->getCustomerOrderDateCreated()) != null) {
                $total_cost = $total_cost + $value->getProduct()->getProductCostByDateDecimal($this->getCustomerOrderDateCreated());
            }  
        }


        //Thirdly, calculate VAT paid
        $ex_vat = round($this->getCustomerOrderTotalPricePaid() / 1.2, 2);
        $vat = $this->getCustomerOrderTotalPricePaid() - $ex_vat;
        $total_cost = $total_cost + $vat;


        //NB - amazon fees for all orders will be aken off in the controller
        

        //Return the total cost
        return $total_cost;
    }

    //Get profit of order 
    public function getOrderProfit()
    {
        //First Check if order was MA or Amazon
        //MA Online
        if($this->getCustomerOrderSource()->getId() == 1) {
            $profit = $this->getCustomerOrderTotalPricePaid() - $this->getMAWebsiteOrderCost();
        //Amazon
        } else if($this->getCustomerOrderSource()->getId() == 2) {
            $profit = $this->getCustomerOrderTotalPricePaid() - $this->getAmazonOrderCost();
        }

        return $profit;
    }
     

    
}
