<?php
// src/MilesApart/AdminBundle/Entity/SupplierDelivery.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierDeliveryRepository")
 * @ORM\Table(name="supplier_delivery")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierDelivery
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
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="supplier_delivery")
     * @ORM\JoinTable(name="supplier")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    protected $supplier;

    /**
     * @ORM\ManyToOne(targetEntity="LogisticsCompany", inversedBy="supplier_delivery")
     * @ORM\JoinTable(name="logistics_company")
     * @ORM\JoinColumn(name="logistics_company_id", referencedColumnName="id")
     */
    protected $logistics_company;

    /**
     * @ORM\ManyToOne(targetEntity="BusinessPremises", inversedBy="supplier_delivery")
     * @ORM\JoinTable(name="business_premises")
     * @ORM\JoinColumn(name="business_premises_id", referencedColumnName="id")
     */
    protected $business_premises;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $booked_in_date;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $booked_in_before_12;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $contact_name;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $contact_phone_number;

    /**
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="supplier_delivery")
     * @ORM\JoinTable(name="employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $delivered_datetime;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $delivered_before_12;

    /**
     * @ORM\OneToMany(targetEntity="SupplierDeliveryProduct", mappedBy="supplier_delivery")
     */
    protected $supplier_delivery_product;

    /**
     * @ORM\ManyToOne(targetEntity="SupplierDeliveryState", inversedBy="supplier_delivery")
     * @ORM\JoinTable(name="supplier_delivery_state")
     * @ORM\JoinColumn(name="supplier_delivery_state_id", referencedColumnName="id")
     */
    protected $supplier_delivery_state;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $supplier_delivery_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $supplier_delivery_date_modified;

     /**
     * @ORM\Column(type="string", length=500, unique=false, nullable=true)
     */
    protected $supplier_delivery_notes;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $supplier_delivery_note_complete;

    /**
     * @ORM\Column(type="string", unique=false, nullable=true)
     */
    protected $supplier_delivery_note_number;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setSupplierDeliveryDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getSupplierDeliveryDateCreated() == null)
        {
            $this->setSupplierDeliveryDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        
        //Contact name
        
        $metadata->addPropertyConstraint('contact_name', new Assert\Length(array(
            'min'        => 1,
            'max'        => 100,
            'minMessage' => 'The contact name must be at least {{ limit }} characters length',
            'maxMessage' => 'The contact name cannot be longer than {{ limit }} characters length',
        )));

        //Contact phone number
        
        $metadata->addPropertyConstraint('contact_phone_number', new Assert\Length(array(
            'min'        => 1,
            'max'        => 20,
            'minMessage' => 'The contact phone number must be at least {{ limit }} characters length',
            'maxMessage' => 'The contact phone number cannot be longer than {{ limit }} characters length',
        )));

        //Booked in date
        $metadata->addPropertyConstraint('booked_in_date', new Assert\Date());

        //Delivered date
        $metadata->addPropertyConstraint('delivered_datetime', new Assert\Date());
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supplier_delivery_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->booked_in_before_12 = false;
        $this->delivered_before_12 = false;
        $this->supplier_delivery_note_complete = false;

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
     * Set booked_in_date
     *
     * @param \Date $bookedInDate
     * @return SupplierDelivery
     */
    public function setBookedInDate($bookedInDate)
    {
        $this->booked_in_date = $bookedInDate;
    
        return $this;
    }

    /**
     * Get booked_in_date
     *
     * @return \Date 
     */
    public function getBookedInDate()
    {
        return $this->booked_in_date;
    }

    /**
     * Set contact_name
     *
     * @param string $contactName
     * @return SupplierDelivery
     */
    public function setContactName($contactName)
    {
        $this->contact_name = $contactName;
    
        return $this;
    }

    /**
     * Get contact_name
     *
     * @return string 
     */
    public function getContactName()
    {
        return $this->contact_name;
    }

    /**
     * Set contact_phone_number
     *
     * @param string $contactPhoneNumber
     * @return SupplierDelivery
     */
    public function setContactPhoneNumber($contactPhoneNumber)
    {
        $this->contact_phone_number = $contactPhoneNumber;
    
        return $this;
    }

    /**
     * Get contact_phone_number
     *
     * @return string 
     */
    public function getContactPhoneNumber()
    {
        return $this->contact_phone_number;
    }

    /**
     * Set delivered_datetime
     *
     * @param \Date $deliveredDatetime
     * @return SupplierDelivery
     */
    public function setDeliveredDatetime($deliveredDatetime)
    {
        $this->delivered_datetime = $deliveredDatetime;
    
        return $this;
    }

    /**
     * Get delivered_datetime
     *
     * @return \Datetime
     */
    public function getDeliveredDatetime()
    {
        return $this->delivered_datetime;
    }

    /**
     * Set supplier_delivery_date_created
     *
     * @param \DateTime $supplierDeliveryDateCreated
     * @return SupplierDelivery
     */
    public function setSupplierDeliveryDateCreated($supplierDeliveryDateCreated)
    {
        $this->supplier_delivery_date_created = $supplierDeliveryDateCreated;
    
        return $this;
    }

    /**
     * Get supplier_delivery_date_created
     *
     * @return \DateTime 
     */
    public function getSupplierDeliveryDateCreated()
    {
        return $this->supplier_delivery_date_created;
    }

    /**
     * Set supplier_delivery_date_modified
     *
     * @param \DateTime $supplierDeliveryDateModified
     * @return SupplierDelivery
     */
    public function setSupplierDeliveryDateModified($supplierDeliveryDateModified)
    {
        $this->supplier_delivery_date_modified = $supplierDeliveryDateModified;
    
        return $this;
    }

    /**
     * Get supplier_delivery_date_modified
     *
     * @return \DateTime 
     */
    public function getSupplierDeliveryDateModified()
    {
        return $this->supplier_delivery_date_modified;
    }

    /**
     * Set supplier
     *
     * @param \MilesApart\AdminBundle\Entity\Supplier $supplier
     * @return SupplierDelivery
     */
    public function setSupplier(\MilesApart\AdminBundle\Entity\Supplier $supplier = null)
    {
        $this->supplier = $supplier;
    
        return $this;
    }

    /**
     * Get supplier
     *
     * @return \MilesApart\AdminBundle\Entity\Supplier 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set logistics_company
     *
     * @param \MilesApart\AdminBundle\Entity\LogisticsCompany $logisticsCompany
     * @return SupplierDelivery
     */
    public function setLogisticsCompany(\MilesApart\AdminBundle\Entity\LogisticsCompany $logisticsCompany = null)
    {
        $this->logistics_company = $logisticsCompany;
    
        return $this;
    }

    /**
     * Get logistics_company
     *
     * @return \MilesApart\AdminBundle\Entity\LogisticsCompany 
     */
    public function getLogisticsCompany()
    {
        return $this->logistics_company;
    }

    /**
     * Set business_premises
     *
     * @param \MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises
     * @return SupplierDelivery
     */
    public function setBusinessPremises(\MilesApart\AdminBundle\Entity\BusinessPremises $businessPremises = null)
    {
        $this->business_premises = $businessPremises;
    
        return $this;
    }

    /**
     * Get business_premises
     *
     * @return \MilesApart\AdminBundle\Entity\BusinessPremises 
     */
    public function getBusinessPremises()
    {
        return $this->business_premises;
    }

    /**
     * Set employee
     *
     * @param \MilesApart\AdminBundle\Entity\Employee $employee
     * @return SupplierDelivery
     */
    public function setEmployee(\MilesApart\AdminBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;
    
        return $this;
    }

    /**
     * Get employee
     *
     * @return \MilesApart\AdminBundle\Entity\Employee 
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Add supplier_delivery_product
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct
     * @return SupplierDelivery
     */
    public function addSupplierDeliveryProduct(\MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct)
    {
        $this->supplier_delivery_product[] = $supplierDeliveryProduct;
    
        return $this;
    }

    /**
     * Remove supplier_delivery_product
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct
     */
    public function removeSupplierDeliveryProduct(\MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct)
    {
        $this->supplier_delivery_product->removeElement($supplierDeliveryProduct);
    }

    /**
     * Get supplier_delivery_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierDeliveryProduct()
    {
        return $this->supplier_delivery_product;
    }

    /**
     * Set supplier_delivery_notes
     *
     * @param string $supplierDeliveryNotes
     * @return SupplierDelivery
     */
    public function setSupplierDeliveryNotes($supplierDeliveryNotes)
    {
        $this->supplier_delivery_notes = $supplierDeliveryNotes;
    
        return $this;
    }

    /**
     * Get supplier_delivery_notes
     *
     * @return string 
     */
    public function getSupplierDeliveryNotes()
    {
        return $this->supplier_delivery_notes;
    }

    /**
     * Set booked_in_before_12
     *
     * @param boolean $bookedInBefore12
     * @return SupplierDelivery
     */
    public function setBookedInBefore12($bookedInBefore12)
    {
        $this->booked_in_before_12 = $bookedInBefore12;

        return $this;
    }

    /**
     * Get booked_in_before_12
     *
     * @return boolean 
     */
    public function getBookedInBefore12()
    {
        return $this->booked_in_before_12;
    }

    /**
     * Set delivered_before_12
     *
     * @param boolean $deliveredBefore12
     * @return SupplierDelivery
     */
    public function setDeliveredBefore12($deliveredBefore12)
    {
        $this->delivered_before_12 = $deliveredBefore12;

        return $this;
    }

    /**
     * Get delivered_before_12
     *
     * @return boolean 
     */
    public function getDeliveredBefore12()
    {
        return $this->delivered_before_12;
    }

    /**
     * Set supplier_delivery_note_complete
     *
     * @param boolean $supplierDeliveryNoteComplete
     * @return SupplierDelivery
     */
    public function setSupplierDeliveryNoteComplete($supplierDeliveryNoteComplete)
    {
        $this->supplier_delivery_note_complete = $supplierDeliveryNoteComplete;

        return $this;
    }

    /**
     * Get supplier_delivery_note_complete
     *
     * @return boolean 
     */
    public function getSupplierDeliveryNoteComplete()
    {
        return $this->supplier_delivery_note_complete;
    }

    /**
     * Set supplier_delivery_note_number
     *
     * @param string $supplierDeliveryNoteNumber
     * @return SupplierDelivery
     */
    public function setSupplierDeliveryNoteNumber($supplierDeliveryNoteNumber)
    {
        $this->supplier_delivery_note_number = $supplierDeliveryNoteNumber;

        return $this;
    }

    /**
     * Get supplier_delivery_note_number
     *
     * @return string 
     */
    public function getSupplierDeliveryNoteNumber()
    {
        return $this->supplier_delivery_note_number;
    }

   

    /**
     * Set supplier_delivery_state
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDeliveryState $supplierDeliveryState
     * @return SupplierDelivery
     */
    public function setSupplierDeliveryState(\MilesApart\AdminBundle\Entity\SupplierDeliveryState $supplierDeliveryState = null)
    {
        $this->supplier_delivery_state = $supplierDeliveryState;

        return $this;
    }

    /**
     * Get supplier_delivery_state
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierDeliveryState 
     */
    public function getSupplierDeliveryState()
    {
        return $this->supplier_delivery_state;
    }

    //Number of lines to be stored 
    public function getSupplierDeliveryLinesToStore()
    {

        $total_lines = 0;    

        //If delivery products exist
        if (count($this->getSupplierDeliveryProduct()) > 0) {

            //Iterate over the delivery lines
            foreach($this->getSupplierDeliveryProduct() as $key => $value) {

                //Icheck to see if the line has product to be stored
                if($value->getSupplierDeliveryQtyToStore() > 0) {

                    //Check how many lives have already been stored
                    if($value->getStockLocationShelfProductSent() != null) {
                        $total_stored = 0;
                        foreach($value->getStockLocationShelfProductSent() as $key2 => $value2) {
                            $total_stored = $total_stored + $value2->getStockLocationShelfProductSentQty();
                        }

                        if($total_stored < $value->getSupplierDeliveryQtyToStore()) {
                            //Add the line to the total
                            $total_lines = $total_lines + 1;
                        }
                    } else {
                        $total_lines = $total_lines + 1;
                    }

                }
            }
        }

        return $total_lines;
    }
}
