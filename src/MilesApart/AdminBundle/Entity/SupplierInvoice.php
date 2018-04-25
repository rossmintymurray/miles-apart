<?php
// src/MilesApart/AdminBundle/Entity/SupplierInvoice.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierInvoiceRepository")
 * @ORM\Table(name="supplier_invoice")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierInvoice
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
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="supplier_invoice")
     * @ORM\JoinTable(name="supplier")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    protected $supplier;

    /**
     * @ORM\ManyToOne(targetEntity="SupplierInvoiceState", inversedBy="supplier_invoice")
     * @ORM\JoinTable(name="supplier_invoice_state")
     * @ORM\JoinColumn(name="supplier_invoice_state_id", referencedColumnName="id")
     */
    protected $supplier_invoice_state;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $supplier_invoice_code;

    /**
     * @ORM\Column(type="date", unique=false, nullable=false)
     */
    protected $supplier_invoice_date;

    /**
     * @ORM\OneToMany(targetEntity="SupplierInvoicePayment", mappedBy="supplier_invoice")
     */
    protected $supplier_invoice_payment;

    /**
     * @ORM\OneToMany(targetEntity="SupplierInvoiceProduct", mappedBy="supplier_invoice")
     */
    protected $supplier_invoice_product;

    /**
     * @ORM\OneToMany(targetEntity="SupplierInvoicePurchaseOrder", mappedBy="supplier_invoice")
     */
    protected $supplier_invoice_purchase_order;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        //Supplier
        $metadata->addPropertyConstraint('supplier', new Assert\Choice(array(
            'callback' => 'getSupplier',
        )));

        //Supplier invoice state
        $metadata->addPropertyConstraint('supplier_invoice_state', new Assert\Choice(array(
            'callback' => 'getAdminUserType',
        )));

        //Supplier invoice code
        $metadata->addPropertyConstraint('supplier_invoice_code', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_invoice_code', new Assert\Length(array(
            'min'        => 1,
            'max'        => 20,
            'minMessage' => 'The supplier invoice code must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier invoice code cannot be longer than {{ limit }} characters length',
        )));

        //Supplier invoice date
        $metadata->addPropertyConstraint('supplier_invoice_date', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_invoice_date', new Assert\Date());
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supplier_invoice_payment = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier_invoice_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier_invoice_purchase_order = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set supplier_invoice_code
     *
     * @param string $supplierInvoiceCode
     * @return SupplierInvoice
     */
    public function setSupplierInvoiceCode($supplierInvoiceCode)
    {
        $this->supplier_invoice_code = $supplierInvoiceCode;
    
        return $this;
    }

    /**
     * Get supplier_invoice_code
     *
     * @return string 
     */
    public function getSupplierInvoiceCode()
    {
        return $this->supplier_invoice_code;
    }

    /**
     * Set supplier_invoice_date
     *
     * @param \DateTime $supplierInvoiceDate
     * @return SupplierInvoice
     */
    public function setSupplierInvoiceDate($supplierInvoiceDate)
    {
        $this->supplier_invoice_date = $supplierInvoiceDate;
    
        return $this;
    }

    /**
     * Get supplier_invoice_date
     *
     * @return \DateTime 
     */
    public function getSupplierInvoiceDate()
    {
        return $this->supplier_invoice_date;
    }

    /**
     * Set supplier
     *
     * @param \MilesApart\AdminBundle\Entity\Supplier $supplier
     * @return SupplierInvoice
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
     * Set supplier_invoice_state
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoiceState $supplierInvoiceState
     * @return SupplierInvoice
     */
    public function setSupplierInvoiceState(\MilesApart\AdminBundle\Entity\SupplierInvoiceState $supplierInvoiceState = null)
    {
        $this->supplier_invoice_state = $supplierInvoiceState;
    
        return $this;
    }

    /**
     * Get supplier_invoice_state
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierInvoiceState 
     */
    public function getSupplierInvoiceState()
    {
        return $this->supplier_invoice_state;
    }

    /**
     * Add supplier_invoice_payment
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoicePayment $supplierInvoicePayment
     * @return SupplierInvoice
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

    /**
     * Add supplier_invoice_product
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoiceProduct $supplierInvoiceProduct
     * @return SupplierInvoice
     */
    public function addSupplierInvoiceProduct(\MilesApart\AdminBundle\Entity\SupplierInvoiceProduct $supplierInvoiceProduct)
    {
        $this->supplier_invoice_product[] = $supplierInvoiceProduct;
    
        return $this;
    }

    /**
     * Remove supplier_invoice_product
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoiceProduct $supplierInvoiceProduct
     */
    public function removeSupplierInvoiceProduct(\MilesApart\AdminBundle\Entity\SupplierInvoiceProduct $supplierInvoiceProduct)
    {
        $this->supplier_invoice_product->removeElement($supplierInvoiceProduct);
    }

    /**
     * Get supplier_invoice_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierInvoiceProduct()
    {
        return $this->supplier_invoice_product;
    }

    /**
     * Add supplier_invoice_purchase_order
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoicePurchaseOrder $supplierInvoicePurchaseOrder
     * @return SupplierInvoice
     */
    public function addSupplierInvoicePurchaseOrder(\MilesApart\AdminBundle\Entity\SupplierInvoicePurchaseOrder $supplierInvoicePurchaseOrder)
    {
        $this->supplier_invoice_purchase_order[] = $supplierInvoicePurchaseOrder;
    
        return $this;
    }

    /**
     * Remove supplier_invoice_purchase_order
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoicePurchaseOrder $supplierInvoicePurchaseOrder
     */
    public function removeSupplierInvoicePurchaseOrder(\MilesApart\AdminBundle\Entity\SupplierInvoicePurchaseOrder $supplierInvoicePurchaseOrder)
    {
        $this->supplier_invoice_purchase_order->removeElement($supplierInvoicePurchaseOrder);
    }

    /**
     * Get supplier_invoice_purchase_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierInvoicePurchaseOrder()
    {
        return $this->supplier_invoice_purchase_order;
    }
}