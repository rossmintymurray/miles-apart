<?php
// src/MilesApart/AdminBundle/Entity/Supplier.php  -- Defines the sub category object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;

use MilesApart\AdminBundle\Utils\MilesApart as MilesApart;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierRepository")
 * @ORM\Table(name="supplier")
 * @ORM\HasLifecycleCallbacks()
 */

class Supplier
{
    //Define the values

    /**
     * @var integer $id
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=200, unique=false, nullable=false)
     */
    protected $supplier_name;

    /**
     * @ORM\Column(type="string", length=30, unique=false, nullable=true)
     */
    protected $supplier_short_name;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $supplier_account_number;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $supplier_address_1;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $supplier_address_2;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=false)
     */
    protected $supplier_town;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=false)
     */
    protected $supplier_county;

    /**
     * @ORM\Column(type="string", length=10, unique=false, nullable=false)
     */
    protected $supplier_postcode;

    /**
     * @ORM\Column(type="string", length=50, unique=false, nullable=false)
     */
    protected $supplier_country;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $supplier_phone;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $supplier_fax;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $supplier_ordering_email;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $supplier_info_email;

    /**
     * @ORM\Column(type="string", length=100, unique=false, nullable=true)
     */
    protected $supplier_website;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
     */
    protected $supplier_minimum_order_value;

    /**
     * @ORM\ManyToOne(targetEntity="SupplierType", inversedBy="supplier")
     * @ORM\JoinTable(name="supplier_type")
     * @ORM\JoinColumn(name="supplier_type_id", referencedColumnName="id")
     */
    protected $supplier_type;

    /**
     * @ORM\OneToMany(targetEntity="SupplierRepresentative", mappedBy="supplier")
     */
    protected $supplier_representative;

    /**
     * @Gedmo\Slug(fields={"supplier_name"}, separator="-")
     * @ORM\Column(type="string", length=200, unique=true, nullable=false)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="ProductSupplier", mappedBy="supplier", fetch="EAGER")
     */
    protected $product_supplier;

    /**
     * @ORM\OneToMany(targetEntity="SupplierDiscount", mappedBy="supplier")
     */
    protected $supplier_discount;

    /**
     * @ORM\OneToMany(targetEntity="PurchaseOrder", mappedBy="supplier")
     */
    protected $purchase_order;

    /**
     * @ORM\OneToMany(targetEntity="SupplierDelivery", mappedBy="supplier")
     */
    protected $supplier_delivery;

    /**
     * @ORM\OneToMany(targetEntity="SupplierInvoice", mappedBy="supplier")
     */
    protected $supplier_invoice;

    /**
     * @ORM\ManyToOne(targetEntity="SupplierMinimumOrderFormat", inversedBy="supplier")
     * @ORM\JoinTable(name="supplier_minimum_order_format")
     * @ORM\JoinColumn(name="supplier_minimum_order_format_id", referencedColumnName="id")
     */
    protected $supplier_minimum_order_format;

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Supplier name
        $metadata->addPropertyConstraint('supplier_name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 200,
            'minMessage' => 'The supplier name must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier name cannot be longer than {{ limit }} characters length',
        )));

        //Supplier short name
        $metadata->addPropertyConstraint('supplier_short_name', new Assert\Length(array(
            'min'        => 2,
            'max'        => 30,
            'minMessage' => 'The supplier short name must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier short name cannot be longer than {{ limit }} characters length',
        )));

        //Supplier account number
        $metadata->addPropertyConstraint('supplier_account_number', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The supplier account number must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier account number cannot be longer than {{ limit }} characters length',
        )));

        //Supplier address 1
        $metadata->addPropertyConstraint('supplier_address_1', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_address_1', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The supplier address 1 must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier address 1 cannot be longer than {{ limit }} characters length',
        )));
        
        //Supplier address 2
        $metadata->addPropertyConstraint('supplier_address_2', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The supplier address 2 must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier address 2 cannot be longer than {{ limit }} characters length',
        )));

        //Supplier town
        $metadata->addPropertyConstraint('supplier_town', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_town', new Assert\Length(array(
            'min'        => 2,
            'max'        => 100,
            'minMessage' => 'The supplier town must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier town cannot be longer than {{ limit }} characters length',
        )));

        //Supplier county
        $metadata->addPropertyConstraint('supplier_county', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_county', new Assert\Length(array(
            'min'        => 2,
            'max'        => 50,
            'minMessage' => 'The supplier county must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier county cannot be longer than {{ limit }} characters length',
        )));

        //Supplier postcode
        $metadata->addPropertyConstraint('supplier_postcode', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_postcode', new Assert\Length(array(
            'min'        => 2,
            'max'        => 10,
            'minMessage' => 'The supplier postcode must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier postcode cannot be longer than {{ limit }} characters length',
        )));

        //Supplier country
        $metadata->addPropertyConstraint('supplier_country', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_country', new Assert\Length(array(
            'min'        => 2,
            'max'        => 50,
            'minMessage' => 'The supplier country must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier country cannot be longer than {{ limit }} characters length',
        )));

        //Supplier phone
        $metadata->addPropertyConstraint('supplier_phone', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_phone', new Assert\Length(array(
            'min'        => 10,
            'max'        => 20,
            'minMessage' => 'The supplier phone must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier phone cannot be longer than {{ limit }} characters length',
        )));

        //Supplier fax
        $metadata->addPropertyConstraint('supplier_fax', new Assert\Length(array(
            'min'        => 10,
            'max'        => 20,
            'minMessage' => 'The supplier fax must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier fax cannot be longer than {{ limit }} characters length',
        )));
     
        //Supplier ordering email
        $metadata->addPropertyConstraint('supplier_ordering_email', new Assert\Email());
        $metadata->addPropertyConstraint('supplier_ordering_email', new Assert\Length(array(
            'min'        => 5,
            'max'        => 100,
            'minMessage' => 'The supplier ordering email must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier ordering email cannot be longer than {{ limit }} characters length',
        )));

        //Supplier info email
        $metadata->addPropertyConstraint('supplier_info_email', new Assert\Email());
        $metadata->addPropertyConstraint('supplier_info_email', new Assert\Length(array(
            'min'        => 5,
            'max'        => 100,
            'minMessage' => 'The supplier info email must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier info email cannot be longer than {{ limit }} characters length',
        )));

        //Supplier website
        $metadata->addPropertyConstraint('supplier_website', new Assert\Url());

        
       
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->supplier_representative = new \Doctrine\Common\Collections\ArrayCollection();
        $this->product_supplier = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier_discount = new \Doctrine\Common\Collections\ArrayCollection();
        $this->purchase_order = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier_delivery = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier_invoice = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set supplier_name
     *
     * @param string $supplierName
     * @return Supplier
     */
    public function setSupplierName($supplierName)
    {
        $this->supplier_name = $supplierName;
    
        return $this;
    }

    /**
     * Get supplier_name
     *
     * @return string 
     */
    public function getSupplierName()
    {
        return $this->supplier_name;
    }

    /**
     * Set supplier_account_number
     *
     * @param string $supplierAccountNumber
     * @return Supplier
     */
    public function setSupplierAccountNumber($supplierAccountNumber)
    {
        $this->supplier_account_number = $supplierAccountNumber;
    
        return $this;
    }

    /**
     * Get supplier_account_number
     *
     * @return string 
     */
    public function getSupplierAccountNumber()
    {
        return $this->supplier_account_number;
    }

    /**
     * Set supplier_address_1
     *
     * @param string $supplierAddress1
     * @return Supplier
     */
    public function setSupplierAddress1($supplierAddress1)
    {
        $this->supplier_address_1 = $supplierAddress1;
    
        return $this;
    }

    /**
     * Get supplier_address_1
     *
     * @return string 
     */
    public function getSupplierAddress1()
    {
        return $this->supplier_address_1;
    }

    /**
     * Set supplier_address_2
     *
     * @param string $supplierAddress2
     * @return Supplier
     */
    public function setSupplierAddress2($supplierAddress2)
    {
        $this->supplier_address_2 = $supplierAddress2;
    
        return $this;
    }

    /**
     * Get supplier_address_2
     *
     * @return string 
     */
    public function getSupplierAddress2()
    {
        return $this->supplier_address_2;
    }

    /**
     * Set supplier_town
     *
     * @param string $supplierTown
     * @return Supplier
     */
    public function setSupplierTown($supplierTown)
    {
        $this->supplier_town = $supplierTown;
    
        return $this;
    }

    /**
     * Get supplier_town
     *
     * @return string 
     */
    public function getSupplierTown()
    {
        return $this->supplier_town;
    }

    /**
     * Set supplier_county
     *
     * @param string $supplierCounty
     * @return Supplier
     */
    public function setSupplierCounty($supplierCounty)
    {
        $this->supplier_county = $supplierCounty;
    
        return $this;
    }

    /**
     * Get supplier_county
     *
     * @return string 
     */
    public function getSupplierCounty()
    {
        return $this->supplier_county;
    }

    /**
     * Set supplier_postcode
     *
     * @param string $supplierPostcode
     * @return Supplier
     */
    public function setSupplierPostcode($supplierPostcode)
    {
        $this->supplier_postcode = $supplierPostcode;
    
        return $this;
    }

    /**
     * Get supplier_postcode
     *
     * @return string 
     */
    public function getSupplierPostcode()
    {
        return $this->supplier_postcode;
    }

    /**
     * Set supplier_country
     *
     * @param string $supplierCountry
     * @return Supplier
     */
    public function setSupplierCountry($supplierCountry)
    {
        $this->supplier_country = $supplierCountry;
    
        return $this;
    }

    /**
     * Get supplier_country
     *
     * @return string 
     */
    public function getSupplierCountry()
    {
        return $this->supplier_country;
    }

    /**
     * Set supplier_phone
     *
     * @param string $supplierPhone
     * @return Supplier
     */
    public function setSupplierPhone($supplierPhone)
    {
        $this->supplier_phone = $supplierPhone;
    
        return $this;
    }

    /**
     * Get supplier_phone
     *
     * @return string 
     */
    public function getSupplierPhone()
    {
        return $this->supplier_phone;
    }

    /**
     * Set supplier_fax
     *
     * @param string $supplierFax
     * @return Supplier
     */
    public function setSupplierFax($supplierFax)
    {
        $this->supplier_fax = $supplierFax;
    
        return $this;
    }

    /**
     * Get supplier_fax
     *
     * @return string 
     */
    public function getSupplierFax()
    {
        return $this->supplier_fax;
    }

    /**
     * Set supplier_ordering_email
     *
     * @param string $supplierOrderingEmail
     * @return Supplier
     */
    public function setSupplierOrderingEmail($supplierOrderingEmail)
    {
        $this->supplier_ordering_email = $supplierOrderingEmail;
    
        return $this;
    }

    /**
     * Get supplier_ordering_email
     *
     * @return string 
     */
    public function getSupplierOrderingEmail()
    {
        return $this->supplier_ordering_email;
    }

    /**
     * Set supplier_info_email
     *
     * @param string $supplierInfoEmail
     * @return Supplier
     */
    public function setSupplierInfoEmail($supplierInfoEmail)
    {
        $this->supplier_info_email = $supplierInfoEmail;
    
        return $this;
    }

    /**
     * Get supplier_info_email
     *
     * @return string 
     */
    public function getSupplierInfoEmail()
    {
        return $this->supplier_info_email;
    }

    /**
     * Set supplier_website
     *
     * @param string $supplierWebsite
     * @return Supplier
     */
    public function setSupplierWebsite($supplierWebsite)
    {
        $this->supplier_website = $supplierWebsite;
    
        return $this;
    }

    /**
     * Get supplier_website
     *
     * @return string 
     */
    public function getSupplierWebsite()
    {
        return $this->supplier_website;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Supplier
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set supplier_type
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierType $supplierType
     * @return Supplier
     */
    public function setSupplierType(\MilesApart\AdminBundle\Entity\SupplierType $supplierType = null)
    {
        $this->supplier_type = $supplierType;
    
        return $this;
    }

    /**
     * Get supplier_type
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierType 
     */
    public function getSupplierType()
    {
        return $this->supplier_type;
    }

    /**
     * Add supplier_representative
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierRepresentative $supplierRepresentative
     * @return Supplier
     */
    public function addSupplierRepresentative(\MilesApart\AdminBundle\Entity\SupplierRepresentative $supplierRepresentative)
    {
        $this->supplier_representative[] = $supplierRepresentative;
    
        return $this;
    }

    /**
     * Remove supplier_representative
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierRepresentative $supplierRepresentative
     */
    public function removeSupplierRepresentative(\MilesApart\AdminBundle\Entity\SupplierRepresentative $supplierRepresentative)
    {
        $this->supplier_representative->removeElement($supplierRepresentative);
    }

    /**
     * Get supplier_representative
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierRepresentative()
    {
        return $this->supplier_representative;
    }

    /**
     * Add product_supplier
     *
     * @param \MilesApart\AdminBundle\Entity\ProductSupplier $productSupplier
     * @return Supplier
     */
    public function addProductSupplier(\MilesApart\AdminBundle\Entity\ProductSupplier $productSupplier)
    {
        $this->product_supplier[] = $productSupplier;
    
        return $this;
    }

    /**
     * Remove product_supplier
     *
     * @param \MilesApart\AdminBundle\Entity\ProductSupplier $productSupplier
     */
    public function removeProductSupplier(\MilesApart\AdminBundle\Entity\ProductSupplier $productSupplier)
    {
        $this->product_supplier->removeElement($productSupplier);
    }

    /**
     * Get product_supplier
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProductSupplier()
    {
        return $this->product_supplier;
    }

    /**
     * Add supplier_discount
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDiscount $supplierDiscount
     * @return Supplier
     */
    public function addSupplierDiscount(\MilesApart\AdminBundle\Entity\SupplierDiscount $supplierDiscount)
    {
        $this->supplier_discount[] = $supplierDiscount;
    
        return $this;
    }

    /**
     * Remove supplier_discount
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDiscount $supplierDiscount
     */
    public function removeSupplierDiscount(\MilesApart\AdminBundle\Entity\SupplierDiscount $supplierDiscount)
    {
        $this->supplier_discount->removeElement($supplierDiscount);
    }

    /**
     * Get supplier_discount
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierDiscount()
    {
        return $this->supplier_discount;
    }

    /**
     * Add purchase_order
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder
     * @return Supplier
     */
    public function addPurchaseOrder(\MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder)
    {
        $this->purchase_order[] = $purchaseOrder;
    
        return $this;
    }

    /**
     * Remove purchase_order
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder
     */
    public function removePurchaseOrder(\MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder)
    {
        $this->purchase_order->removeElement($purchaseOrder);
    }

    /**
     * Get purchase_order
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPurchaseOrder()
    {
        return $this->purchase_order;
    }

    /**
     * Add supplier_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery
     * @return Supplier
     */
    public function addSupplierDelivery(\MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery)
    {
        $this->supplier_delivery[] = $supplierDelivery;
    
        return $this;
    }

    /**
     * Remove supplier_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery
     */
    public function removeSupplierDelivery(\MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery)
    {
        $this->supplier_delivery->removeElement($supplierDelivery);
    }

    /**
     * Get supplier_delivery
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierDelivery()
    {
        return $this->supplier_delivery;
    }

    /**
     * Add supplier_invoice
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice
     * @return Supplier
     */
    public function addSupplierInvoice(\MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice)
    {
        $this->supplier_invoice[] = $supplierInvoice;
    
        return $this;
    }

    /**
     * Remove supplier_invoice
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice
     */
    public function removeSupplierInvoice(\MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice)
    {
        $this->supplier_invoice->removeElement($supplierInvoice);
    }

    /**
     * Get supplier_invoice
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSupplierInvoice()
    {
        return $this->supplier_invoice;
    }

    /**
     * Set supplier_minimum_order_value
     *
     * @param string $supplierMinimumOrderValue
     * @return Supplier
     */
    public function setSupplierMinimumOrderValue($supplierMinimumOrderValue)
    {
        $this->supplier_minimum_order_value = $supplierMinimumOrderValue;
    
        return $this;
    }

    /**
     * Get supplier_minimum_order_value
     *
     * @return string 
     */
    public function getSupplierMinimumOrderValue()
    {
        return $this->supplier_minimum_order_value;
    }

    /**
     * Set supplier_short_name
     *
     * @param string $supplierShortName
     * @return Supplier
     */
    public function setSupplierShortName($supplierShortName)
    {
        $this->supplier_short_name = $supplierShortName;
    
        return $this;
    }

    /**
     * Get supplier_short_name
     *
     * @return string 
     */
    public function getSupplierShortName()
    {
        return $this->supplier_short_name;
    }

    /**
     * Set supplier_minimum_order_format
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierMinimumOrderFormat $supplierMinimumOrderFormat
     * @return Supplier
     */
    public function setSupplierMinimumOrderFormat(\MilesApart\AdminBundle\Entity\SupplierMinimumOrderFormat $supplierMinimumOrderFormat = null)
    {
        $this->supplier_minimum_order_format = $supplierMinimumOrderFormat;

        return $this;
    }

    /**
     * Get supplier_minimum_order_format
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierMinimumOrderFormat 
     */
    public function getSupplierMinimumOrderFormat()
    {
        return $this->supplier_minimum_order_format;
    }
}
