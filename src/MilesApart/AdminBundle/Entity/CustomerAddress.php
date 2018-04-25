<?php
// src/MilesApart/AdminBundle/Entity/CustomerAddress.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\CustomerAddressRepository")
 * @ORM\Table(name="customer_address")
 * @ORM\HasLifecycleCallbacks()
 */

class CustomerAddress
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
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $customer_address_name;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $customer_address_contact_first_name;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $customer_address_contact_surname;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $customer_address_line_1;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $customer_address_line_2;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $customer_address_town;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $customer_address_county;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $customer_address_postcode;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $customer_address_country;

    /**
     * @ORM\Column(type="string", length=500, unique=false, nullable=true)
     */
    protected $customer_address_notes;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $customer_address_is_billing;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $customer_address_default_delivery;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $customer_address_is_inactive;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $customer_address_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $customer_address_date_modified;

    /**
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customer_address", cascade={"persist"})
     * @ORM\JoinTable(name="customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * @ORM\OneToMany(targetEntity="CustomerOrder", mappedBy="delivery_address", cascade={"persist"})
     */
    protected $customer_order_delivery;

    /**
     * @ORM\OneToMany(targetEntity="CustomerOrder", mappedBy="billing_address", cascade={"persist"})
     */
    protected $customer_order_billing;



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setCustomerAddressDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getCustomerAddressDateCreated() == null)
        {
            $this->setCustomerAddressDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }


    
    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Customer address 1
        $metadata->addPropertyConstraint('customer_address_line_1', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_address_line_1', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The address line 1 must be at least {{ limit }} characters length',
            'maxMessage' => 'The address line 1 cannot be longer than {{ limit }} characters length',
        )));

        //Customer address 2
        $metadata->addPropertyConstraint('customer_address_line_2', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The address line 2 must be at least {{ limit }} characters length',
            'maxMessage' => 'The address line 2 cannot be longer than {{ limit }} characters length',
        )));

        //Customer town
        $metadata->addPropertyConstraint('customer_address_town', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_address_town', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The town must be at least {{ limit }} characters length',
            'maxMessage' => 'The town cannot be longer than {{ limit }} characters length',
        )));

        //Customer county
        $metadata->addPropertyConstraint('customer_address_county', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_address_county', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The county must be at least {{ limit }} characters length',
            'maxMessage' => 'The county cannot be longer than {{ limit }} characters length',
        )));


        //Customer postcode
        $metadata->addPropertyConstraint('customer_address_postcode', new Assert\NotBlank());
        $metadata->addPropertyConstraint('customer_address_postcode', new Assert\Length(array(
            'min'        => 5,
            'max'        => 10,
            'minMessage' => 'The postcode must be at least {{ limit }} characters length',
            'maxMessage' => 'The postcode cannot be longer than {{ limit }} characters length',
        )));

        //Customer address notes
        $metadata->addPropertyConstraint('customer_address_notes', new Assert\Length(array(
            'min'        => 1,
            'max'        => 500,
            'minMessage' => 'The address notes must be at least {{ limit }} characters length',
            'maxMessage' => 'The address notes cannot be longer than {{ limit }} characters length',
        )));

        
       
    }




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customer_order_delivery = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customer_order_billing = new \Doctrine\Common\Collections\ArrayCollection();

        $this->customer_address_is_billing = true;
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
     * Set customer_address_name
     *
     * @param string $customerAddressName
     * @return CustomerAddress
     */
    public function setCustomerAddressName($customerAddressName)
    {
        $this->customer_address_name = $customerAddressName;
    
        return $this;
    }

    /**
     * Get customer_address_name
     *
     * @return string 
     */
    public function getCustomerAddressName()
    {
        return $this->customer_address_name;
    }

    /**
     * Set customer_address_line_1
     *
     * @param string $customerAddressLine1
     * @return CustomerAddress
     */
    public function setCustomerAddressLine1($customerAddressLine1)
    {
        $this->customer_address_line_1 = $customerAddressLine1;
    
        return $this;
    }

    /**
     * Get customer_address_line_1
     *
     * @return string 
     */
    public function getCustomerAddressLine1()
    {
        return $this->customer_address_line_1;
    }

    /**
     * Set customer_address_line_2
     *
     * @param string $customerAddressLine2
     * @return CustomerAddress
     */
    public function setCustomerAddressLine2($customerAddressLine2)
    {
        $this->customer_address_line_2 = $customerAddressLine2;
    
        return $this;
    }

    /**
     * Get customer_address_line_2
     *
     * @return string 
     */
    public function getCustomerAddressLine2()
    {
        return $this->customer_address_line_2;
    }

    /**
     * Set customer_address_town
     *
     * @param string $customerAddressTown
     * @return CustomerAddress
     */
    public function setCustomerAddressTown($customerAddressTown)
    {
        $this->customer_address_town = $customerAddressTown;
    
        return $this;
    }

    /**
     * Get customer_address_town
     *
     * @return string 
     */
    public function getCustomerAddressTown()
    {
        return $this->customer_address_town;
    }

    /**
     * Set customer_address_county
     *
     * @param string $customerAddressCounty
     * @return CustomerAddress
     */
    public function setCustomerAddressCounty($customerAddressCounty)
    {
        $this->customer_address_county = $customerAddressCounty;
    
        return $this;
    }

    /**
     * Get customer_address_county
     *
     * @return string 
     */
    public function getCustomerAddressCounty()
    {
        return $this->customer_address_county;
    }

    /**
     * Set customer_address_postcode
     *
     * @param string $customerAddressPostcode
     * @return CustomerAddress
     */
    public function setCustomerAddressPostcode($customerAddressPostcode)
    {
        $this->customer_address_postcode = $customerAddressPostcode;
    
        return $this;
    }

    /**
     * Get customer_address_postcode
     *
     * @return string 
     */
    public function getCustomerAddressPostcode()
    {
        return $this->customer_address_postcode;
    }

    /**
     * Set customer_address_country
     *
     * @param string $customerAddressCountry
     * @return CustomerAddress
     */
    public function setCustomerAddressCountry($customerAddressCountry)
    {
        $this->customer_address_country = $customerAddressCountry;
    
        return $this;
    }

    /**
     * Get customer_address_country
     *
     * @return string 
     */
    public function getCustomerAddressCountry()
    {
        return $this->customer_address_country;
    }

    /**
     * Set customer_address_notes
     *
     * @param string $customerAddressNotes
     * @return CustomerAddress
     */
    public function setCustomerAddressNotes($customerAddressNotes)
    {
        $this->customer_address_notes = $customerAddressNotes;
    
        return $this;
    }

    /**
     * Get customer_address_notes
     *
     * @return string 
     */
    public function getCustomerAddressNotes()
    {
        return $this->customer_address_notes;
    }

    /**
     * Set customer_address_is_billing
     *
     * @param boolean $customerAddressIsBilling
     * @return CustomerAddress
     */
    public function setCustomerAddressIsBilling($customerAddressIsBilling)
    {
        $this->customer_address_is_billing = $customerAddressIsBilling;
    
        return $this;
    }

    /**
     * Get customer_address_is_billing
     *
     * @return boolean 
     */
    public function getCustomerAddressIsBilling()
    {
        return $this->customer_address_is_billing;
    }

    /**
     * Set customer_address_default_delivery
     *
     * @param boolean $customerAddressDefaultDelivery
     * @return CustomerAddress
     */
    public function setCustomerAddressDefaultDelivery($customerAddressDefaultDelivery)
    {
        $this->customer_address_default_delivery = $customerAddressDefaultDelivery;
    
        return $this;
    }

    /**
     * Get customer_address_default_delivery
     *
     * @return boolean 
     */
    public function getCustomerAddressDefaultDelivery()
    {
        return $this->customer_address_default_delivery;
    }

    /**
     * Set customer_address_is_inactive
     *
     * @param boolean $customerAddressIsInactive
     * @return CustomerAddress
     */
    public function setCustomerAddressIsInactive($customerAddressIsInactive)
    {
        $this->customer_address_is_inactive = $customerAddressIsInactive;
    
        return $this;
    }

    /**
     * Get customer_address_is_inactive
     *
     * @return boolean 
     */
    public function getCustomerAddressIsInactive()
    {
        return $this->customer_address_is_inactive;
    }

    /**
     * Set customer_address_date_created
     *
     * @param \DateTime $customerAddressDateCreated
     * @return CustomerAddress
     */
    public function setCustomerAddressDateCreated($customerAddressDateCreated)
    {
        $this->customer_address_date_created = $customerAddressDateCreated;
    
        return $this;
    }

    /**
     * Get customer_address_date_created
     *
     * @return \DateTime 
     */
    public function getCustomerAddressDateCreated()
    {
        return $this->customer_address_date_created;
    }

    /**
     * Set customer_address_date_modified
     *
     * @param \DateTime $customerAddressDateModified
     * @return CustomerAddress
     */
    public function setCustomerAddressDateModified($customerAddressDateModified)
    {
        $this->customer_address_date_modified = $customerAddressDateModified;
    
        return $this;
    }

    /**
     * Get customer_address_date_modified
     *
     * @return \DateTime 
     */
    public function getCustomerAddressDateModified()
    {
        return $this->customer_address_date_modified;
    }

    /**
     * Set customer
     *
     * @param \MilesApart\AdminBundle\Entity\Customer $customer
     * @return CustomerAddress
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
     * Add customer_order_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrderDelivery
     * @return CustomerAddress
     */
    public function addCustomerOrderDelivery(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrderDelivery)
    {
        $this->customer_order_delivery[] = $customerOrderDelivery;
    
        return $this;
    }

    /**
     * Remove customer_order_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrderDelivery
     */
    public function removeCustomerOrderDelivery(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrderDelivery)
    {
        $this->customer_order_delivery->removeElement($customerOrderDelivery);
    }

    /**
     * Get customer_order_delivery
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOrderDelivery()
    {
        return $this->customer_order_delivery;
    }

    /**
     * Add customer_order_billing
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrderBilling
     * @return CustomerAddress
     */
    public function addCustomerOrderBilling(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrderBilling)
    {
        $this->customer_order_billing[] = $customerOrderBilling;
    
        return $this;
    }

    /**
     * Remove customer_order_billing
     *
     * @param \MilesApart\AdminBundle\Entity\CustomerOrder $customerOrderBilling
     */
    public function removeCustomerOrderBilling(\MilesApart\AdminBundle\Entity\CustomerOrder $customerOrderBilling)
    {
        $this->customer_order_billing->removeElement($customerOrderBilling);
    }

    /**
     * Get customer_order_billing
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCustomerOrderBilling()
    {
        return $this->customer_order_billing;
    }

    /**
     * Set customer_address_contact_first_name
     *
     * @param string $customerAddressContactFirstName
     * @return CustomerAddress
     */
    public function setCustomerAddressContactFirstName($customerAddressContactFirstName)
    {
        $this->customer_address_contact_first_name = $customerAddressContactFirstName;
    
        return $this;
    }

    /**
     * Get customer_address_contact_first_name
     *
     * @return string 
     */
    public function getCustomerAddressContactFirstName()
    {
        return $this->customer_address_contact_first_name;
    }

    /**
     * Set customer_address_contact_surname
     *
     * @param string $customerAddressContactSurname
     * @return CustomerAddress
     */
    public function setCustomerAddressContactSurname($customerAddressContactSurname)
    {
        $this->customer_address_contact_surname = $customerAddressContactSurname;
    
        return $this;
    }

    /**
     * Get customer_address_contact_surname
     *
     * @return string 
     */
    public function getCustomerAddressContactSurname()
    {
        return $this->customer_address_contact_surname;
    }

    /**
     * Get customer_address_contact_full_name
     *
     * @return string 
     */
    public function getCustomerAddressContactFullName()
    {

        //Check that contact first and surna,mes exist
        if($this->getCustomerAddressContactFirstName() != null && $this->getCustomerAddressContactSurname()) {
            return $this->getCustomerAddressContactFirstName() . " " .$this->getCustomerAddressContactSurname();
        } else {
            
            return null;
        
        }
        
    }
}