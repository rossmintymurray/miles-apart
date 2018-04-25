<?php
// src/MilesApart/AdminBundle/Entity/Customer.php -- Defines the customer object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerRepository")
 * @ORM\Table(name="customer")
 * @ORM\HasLifecycleCallbacks()
 */

class Customer
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
    protected $customer_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $customer_date_modified;

    /**
     * @ORM\Column(type="boolean",  nullable=false)
     */
    protected $vat_invoice_option;

    /**
     * @ORM\ManyToOne(targetEntity="CustomerType", inversedBy="customer")
     * @ORM\JoinTable(name="customer_type")
     * @ORM\JoinColumn(name="customer_type_id", referencedColumnName="id")
     */
    protected $customer_type;

    /**
     * @ORM\OneToMany(targetEntity="ProductQuestion", mappedBy="customer")
     */
     protected $product_question;

    
    /**
     * @ORM\OneToOne(targetEntity="PersonalCustomer", mappedBy="customer", cascade={"persist"})
     */
    protected $personal_customer;

    /**
     * @ORM\OneToOne(targetEntity="BusinessCustomer", mappedBy="customer", cascade={"persist"})
     */
    protected $business_customer;

    /**
     * @ORM\OneToMany(targetEntity="CustomerAddress", mappedBy="customer", cascade={"persist"})
     */
    protected $customer_address;

    /**
     * @ORM\OneToMany(targetEntity="CustomerWishList", mappedBy="customer")
     */
    protected $customer_wish_list;

    /**
     * @ORM\OneToMany(targetEntity="CustomerOrder", mappedBy="customer", cascade={"persist"})
     */
    protected $customer_order;

    /**
     * @ORM\OneToMany(targetEntity="EmailSendListCustomer", mappedBy="customer")
     */
    protected $email_send_list_customer;

    /**
     * @ORM\OneToMany(targetEntity="\MilesApart\BasketBundle\Entity\Basket", mappedBy="customer", cascade={"persist"})
     */
    protected $basket;

    /**
     * @ORM\OneToMany(targetEntity="MilesApart\AdminBundle\Entity\ProductReview", mappedBy="customer")
     */
    protected $product_review;

    /**
     * @ORM\OneToMany(targetEntity="CustomerInteraction", mappedBy="customer")
     */
    protected $customer_interaction;

    /**
     * @ORM\OneToMany(targetEntity="CustomerOptIn", mappedBy="customer")
     */
    protected $customer_opt_in;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setCustomerDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCustomerDateCreated() == null)
        {
            $this->setCustomerDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Customer date created
        $metadata->addPropertyConstraint('customer_date_created', new Assert\DateTime());

        //Customer date modified
        $metadata->addPropertyConstraint('customer_date_modified', new Assert\DateTime());
        
    }



    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product_question = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customer_address = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customer_wish_list = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customer_order = new \Doctrine\Common\Collections\ArrayCollection();
        $this->email_send_list_customer = new \Doctrine\Common\Collections\ArrayCollection();
        $this->basket = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_review = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customer_interaction = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customer_opt_in = new \Doctrine\Common\Collections\ArrayCollection();

        $this->vat_invoice_option = FALSE;
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

    public function getCustomerFullName() 
    {
        //If it's a personal customer
        if($this->getPersonalCustomer() != null) {
            $name = $this->getPersonalCustomer()->getPersonalCustomerFullName();

        //If its a business customer with rep
        } else if($this->getBusinessCustomer() != null) {
            if($this->getBusinessCustomer()->getBusinessCustomerRepresentative() != null) {
                foreach($this->getBusinessCustomer()->getBusinessCustomerRepresentative() as $rep) {
                    $name = $rep->getBusinessCustomerRepresentativeFullName();
                }
            } 
        } else {
            $name = null;
        }

        return $name;
    }

    public function getCustomerEmailAddress() 
    {
        //If it's a personal customer
        if($this->getPersonalCustomer() != null) {
            $name = $this->getPersonalCustomer()->getPersonalCustomerEmailAddress();

        //If its a business customer with rep
        } else if($this->getBusinessCustomer() != null) {
            if($this->getBusinessCustomer()->getBusinessCustomerRepresentative() != null) {
                foreach($this->getBusinessCustomer()->getBusinessCustomerRepresentative() as $rep) {
                    $name = $rep->getBusinessCustomerRepresentativeEmailAddress();
                }
            } 
        } else {
            $name = null;
        }

        return $name;
    }


    /**
     * Set customer_date_created
     *
     * @param \DateTime $customerDateCreated
     * @return Customer
     */
    public function setCustomerDateCreated($customerDateCreated)
    {
        $this->customer_date_created = $customerDateCreated;

        return $this;
    }

    /**
     * Get customer_date_created
     *
     * @return \DateTime 
     */
    public function getCustomerDateCreated()
    {
        return $this->customer_date_created;
    }

    /**
     * Set customer_date_modified
     *
     * @param \DateTime $customerDateModified
     * @return Customer
     */
    public function setCustomerDateModified($customerDateModified)
    {
        $this->customer_date_modified = $customerDateModified;

        return $this;
    }

    /**
     * Get customer_date_modified
     *
     * @return \DateTime 
     */
    public function getCustomerDateModified()
    {
        return $this->customer_date_modified;
    }

    /**
     * Set vat_invoice_option
     *
     * @param boolean $vatInvoiceOption
     * @return Customer
     */
    public function setVatInvoiceOption($vatInvoiceOption)
    {
        $this->vat_invoice_option = $vatInvoiceOption;

        return $this;
    }

    /**
     * Get vat_invoice_option
     *
     * @return boolean 
     */
    public function getVatInvoiceOption()
    {
        return $this->vat_invoice_option;
    }

    /**
     * Set customer_type
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerType $customerType
     * @return Customer
     */
    public function setCustomerType(\MilesApart\AdminBundle\Entity\CustomerType $customerType = null)
    {
        $this->customer_type = $customerType;

        return $this;
    }

    /**
     * Get customer_type
     *
     * @return \MilesApart\AdminBundle\Entity\CustomerType 
     */
    public function getCustomerType()
    {
        return $this->customer_type;
    }

    /**
     * Add product_question
     *
     * @param \MilesApart\AdminBundle\Entity\ProductQuestion $productQuestion
     * @return Customer
     */
    public function addProductQuestion(\MilesApart\AdminBundle\Entity\ProductQuestion $productQuestion)
    {
        $this->product_question[] = $productQuestion;

        return $this;
    }

    /**
     * Remove product_question
     *
     * @param \MilesApart\AdminBundle\Entity\ProductQuestion $productQuestion
     */
    public function removeProductQuestion(\MilesApart\AdminBundle\Entity\ProductQuestion $productQuestion)
    {
        $this->product_question->removeElement($productQuestion);
    }

    /**
     * Get product_question
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductQuestion()
    {
        return $this->product_question;
    }

    /**
     * Set personal_customer
     *
     * @param \MilesApart\AdminBundle\Entity\PersonalCustomer $personalCustomer
     * @return Customer
     */
    public function setPersonalCustomer(\MilesApart\AdminBundle\Entity\PersonalCustomer $personalCustomer = null)
    {
        $this->personal_customer = $personalCustomer;
        $personalCustomer->setCustomer($this);

        return $this;
    }

    /**
     * Get personal_customer
     *
     * @return \MilesApart\AdminBundle\Entity\PersonalCustomer 
     */
    public function getPersonalCustomer()
    {
        return $this->personal_customer;
    }

    /**
     * Set business_customer
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessCustomer $businessCustomer
     * @return Customer
     */
    public function setBusinessCustomer(\MilesApart\AdminBundle\Entity\BusinessCustomer $businessCustomer = null)
    {
        $this->business_customer = $businessCustomer;
        $businessCustomer->setCustomer($this);

        return $this;
    }

    /**
     * Get business_customer
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessCustomer 
     */
    public function getBusinessCustomer()
    {
        return $this->business_customer;
    }

    /**
     * Add customer_address
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerAddress $customerAddress
     * @return Customer
     */
    public function addCustomerAddress(\MilesApart\AdminBundle\Entity\CustomerAddress $customerAddress)
    {
        $this->customer_address[] = $customerAddress;
        $customerAddress->setCustomer($this);

        return $this;
    }

    /**
     * Remove customer_address
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerAddress $customerAddress
     */
    public function removeCustomerAddress(\MilesApart\AdminBundle\Entity\CustomerAddress $customerAddress)
    {
        $this->customer_address->removeElement($customerAddress);
    }

    /**
     * Get customer_address
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerAddress()
    {
        return $this->customer_address;
    }

    /**
     * Get customer_address
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActiveCustomerAddress()
    {
        $active_addresses = array();
        
        //Check active
        foreach($this->getCustomerAddress() as $key => $value) {
            
            if($value->getCustomerAddressIsInactive() == FALSE) {
                array_push($active_addresses, $value);
            }
            
        } 

        return $active_addresses;
    } 
   
    /**
     * Add customer_wish_list
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerWishList $customerWishList
     * @return Customer
     */
    public function addCustomerWishList(\MilesApart\AdminBundle\Entity\CustomerWishList $customerWishList)
    {
        $this->customer_wish_list[] = $customerWishList;

        return $this;
    }

    /**
     * Remove customer_wish_list
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerWishList $customerWishList
     */
    public function removeCustomerWishList(\MilesApart\AdminBundle\Entity\CustomerWishList $customerWishList)
    {
        $this->customer_wish_list->removeElement($customerWishList);
    }

    /**
     * Get customer_wish_list
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerWishList()
    {
        return $this->customer_wish_list;
    }

    /**
     * Add customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder
     * @return Customer
     */
    public function addCustomerOrder(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder)
    {
        $this->customer_order[] = $customerOrder;

        return $this;
    }

    /**
     * Remove customer_order
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder
     */
    public function removeCustomerOrder(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrder)
    {
        $this->customer_order->removeElement($customerOrder);
    }

    /**
     * Get customer_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOrder()
    {
        return $this->customer_order;
    }

    /**
     * Get customer_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCurrentCustomerOrder()
    {
        //Set up current order array
        $current_order_array = array();

        //If at least one price exists
       if (count($this->getCustomerOrder()) > 0) {

            //For each price 
            foreach($this->getCustomerOrder() as $key => $value) {
            
                //Check valid from is less than (before) or equal to the date.
                if($value->getCustomerOrderState()->getId() < 8) {
                    
                    array_push($current_order_array, $value);
                    
                } 
            } 
        } else {
            $current_order_array = null;
        }

        return $current_order_array;
    }

    /**
     * Get customer_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPreviousCustomerOrder()
    {
        //Set up previous order array
        $previous_order_array = array();

        //If at least one price exists
       if (count($this->getCustomerOrder()) > 0) {

            //For each price 
            foreach($this->getCustomerOrder() as $key => $value) {
            
                //Check valid from is less than (before) or equal to the date.
                if($value->getCustomerOrderState()->getId() == 8) {
                    
                    array_push($previous_order_array, $value);
                    
                } 
            } 
        } else {
            $previous_order_array = null;
        }

        return $previous_order_array;
    }

    /**
     * Add email_send_list_customer
     *
     * @param \MilesApart\AdminBundle\Entity\EmailSendListCustomer $emailSendListCustomer
     * @return Customer
     */
    public function addEmailSendListCustomer(\MilesApart\AdminBundle\Entity\EmailSendListCustomer $emailSendListCustomer)
    {
        $this->email_send_list_customer[] = $emailSendListCustomer;

        return $this;
    }

    /**
     * Remove email_send_list_customer
     *
     * @param \MilesApart\AdminBundle\Entity\EmailSendListCustomer $emailSendListCustomer
     */
    public function removeEmailSendListCustomer(\MilesApart\AdminBundle\Entity\EmailSendListCustomer $emailSendListCustomer)
    {
        $this->email_send_list_customer->removeElement($emailSendListCustomer);
    }

    /**
     * Get email_send_list_customer
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmailSendListCustomer()
    {
        return $this->email_send_list_customer;
    }

    /**
     * Add basket
     *
     * @param \MilesApart\BasketBundle\Entity\Basket $basket
     * @return Customer
     */
    public function addBasket(\MilesApart\BasketBundle\Entity\Basket $basket)
    {
        $this->basket[] = $basket;

        return $this;
    }

    /**
     * Remove basket
     *
     * @param \MilesApart\BasketBundle\Entity\Basket $basket
     */
    public function removeBasket(\MilesApart\BasketBundle\Entity\Basket $basket)
    {
        $this->basket->removeElement($basket);
    }

    /**
     * Get basket
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBasket()
    {
        return $this->basket;
    }

    /**
     * Add product_review
     *
     * @param \MilesApart\AdminBundle\Entity\ProductReview $productReview
     * @return Customer
     */
    public function addProductReview(\MilesApart\AdminBundle\Entity\ProductReview $productReview)
    {
        $this->product_review[] = $productReview;

        return $this;
    }

    /**
     * Remove product_review
     *
     * @param \MilesApart\AdminBundle\Entity\ProductReview $productReview
     */
    public function removeProductReview(\MilesApart\AdminBundle\Entity\ProductReview $productReview)
    {
        $this->product_review->removeElement($productReview);
    }

    /**
     * Get product_review
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductReview()
    {
        return $this->product_review;
    }

    /**
     * Add customer_interaction
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerInteraction $customerInteraction
     * @return Customer
     */
    public function addCustomerInteraction(\MilesApart\AdminBundle\Entity\CustomerInteraction $customerInteraction)
    {
        $this->customer_interaction[] = $customerInteraction;

        return $this;
    }

    /**
     * Remove customer_interaction
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerInteraction $customerInteraction
     */
    public function removeCustomerInteraction(\MilesApart\AdminBundle\Entity\CustomerInteraction $customerInteraction)
    {
        $this->customer_interaction->removeElement($customerInteraction);
    }

    /**
     * Get customer_interaction
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerInteraction()
    {
        return $this->customer_interaction;
    }

    /**
     * Add customer_opt_in
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOptIn $customerOptIn
     * @return Customer
     */
    public function addCustomerOptIn(\MilesApart\AdminBundle\Entity\CustomerOptIn $customerOptIn)
    {
        $this->customer_opt_in[] = $customerOptIn;

        return $this;
    }

    /**
     * Remove customer_opt_in
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOptIn $customerOptIn
     */
    public function removeCustomerOptIn(\MilesApart\AdminBundle\Entity\CustomerOptIn $customerOptIn)
    {
        $this->customer_opt_in->removeElement($customerOptIn);
    }

    /**
     * Get customer_opt_in
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOptIn()
    {
        return $this->customer_opt_in;
    }
}
