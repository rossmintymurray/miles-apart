<?php
// src/MilesApart/AdminBundle/Entity/SupplierPayment.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierPaymentRepository")
 * @ORM\Table(name="supplier_payment")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierPayment
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
     * @ORM\ManyToOne(targetEntity="SupplierPaymentType", inversedBy="supplier_payment")
     * @ORM\JoinTable(name="supplier_payment_type")
     * @ORM\JoinColumn(name="supplier_payment_type_id", referencedColumnName="id")
     */
    protected $supplier_payment_type;

    /**
     * @ORM\Column(type="decimal", precision=4, scale=2, nullable=false)
     */
    protected $supplier_payment_amount;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $supplier_payment_date_paid;

    /**
     * @ORM\OneToMany(targetEntity="SupplierInvoicePayment", mappedBy="supplier_payment")
     */
    protected $supplier_invoice_payment;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $supplier_invoice_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $supplier_invoice_date_modified;
    


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setSupplierInvoiceDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getSupplierInvoiceDateCreated() == null)
        {
            $this->setSupplierInvoiceDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        //Supplier payment amount
        $metadata->addPropertyConstraint('supplier_payment_amount', new Assert\NotBlank());

        //Supplier invoice date paid
        $metadata->addPropertyConstraint('supplier_payment_date_paid', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_payment_date_paid', new Assert\Date());
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supplier_invoice_payment = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set supplier_payment_amount
     *
     * @param float $supplierPaymentAmount
     * @return SupplierPayment
     */
    public function setSupplierPaymentAmount($supplierPaymentAmount)
    {
        $this->supplier_payment_amount = $supplierPaymentAmount;
    
        return $this;
    }

    /**
     * Get supplier_payment_amount
     *
     * @return float 
     */
    public function getSupplierPaymentAmount()
    {
        return $this->supplier_payment_amount;
    }

    /**
     * Set supplier_payment_date_paid
     *
     * @param \DateTime $supplierPaymentDatePaid
     * @return SupplierPayment
     */
    public function setSupplierPaymentDatePaid($supplierPaymentDatePaid)
    {
        $this->supplier_payment_date_paid = $supplierPaymentDatePaid;
    
        return $this;
    }

    /**
     * Get supplier_payment_date_paid
     *
     * @return \DateTime 
     */
    public function getSupplierPaymentDatePaid()
    {
        return $this->supplier_payment_date_paid;
    }

    /**
     * Set supplier_invoice_date_created
     *
     * @param \DateTime $supplierInvoiceDateCreated
     * @return SupplierPayment
     */
    public function setSupplierInvoiceDateCreated($supplierInvoiceDateCreated)
    {
        $this->supplier_invoice_date_created = $supplierInvoiceDateCreated;
    
        return $this;
    }

    /**
     * Get supplier_invoice_date_created
     *
     * @return \DateTime 
     */
    public function getSupplierInvoiceDateCreated()
    {
        return $this->supplier_invoice_date_created;
    }

    /**
     * Set supplier_invoice_date_modified
     *
     * @param \DateTime $supplierInvoiceDateModified
     * @return SupplierPayment
     */
    public function setSupplierInvoiceDateModified($supplierInvoiceDateModified)
    {
        $this->supplier_invoice_date_modified = $supplierInvoiceDateModified;
    
        return $this;
    }

    /**
     * Get supplier_invoice_date_modified
     *
     * @return \DateTime 
     */
    public function getSupplierInvoiceDateModified()
    {
        return $this->supplier_invoice_date_modified;
    }

    /**
     * Set supplier_payment_type
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierPaymentType $supplierPaymentType
     * @return SupplierPayment
     */
    public function setSupplierPaymentType(\MilesApart\AdminBundle\Entity\SupplierPaymentType $supplierPaymentType = null)
    {
        $this->supplier_payment_type = $supplierPaymentType;
    
        return $this;
    }

    /**
     * Get supplier_payment_type
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierPaymentType 
     */
    public function getSupplierPaymentType()
    {
        return $this->supplier_payment_type;
    }

    /**
     * Add supplier_invoice_payment
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoicePayment $supplierInvoicePayment
     * @return SupplierPayment
     */
    public function addSupplierInvoicePayment(\MilesApart\AdminBundle\Entity\SupplierInvoicePayment $supplierInvoicePayment)
    {
        $this->supplier_invoice_payment[] = $supplierInvoicePayment;
    
        return $this;
    }

    /**
     * Remove supplier_invoice_payment
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoicePayment $supplierInvoicePayment
     */
    public function removeSupplierInvoicePayment(\MilesApart\AdminBundle\Entity\SupplierInvoicePayment $supplierInvoicePayment)
    {
        $this->supplier_invoice_payment->removeElement($supplierInvoicePayment);
    }

    /**
     * Get supplier_invoice_payment
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierInvoicePayment()
    {
        return $this->supplier_invoice_payment;
    }
}