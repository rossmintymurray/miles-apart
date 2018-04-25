<?php
// src/MilesApart/AdminBundle/Entity/SupplierDeliveryProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierDeliveryProductRepository")
 * @ORM\Table(name="supplier_delivery_product")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierDeliveryProduct
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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="supplier_delivery_product")
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="SupplierDelivery", inversedBy="supplier_delivery_product")
     * @ORM\JoinTable(name="supplier_delivery")
     * @ORM\JoinColumn(name="supplier_delivery_id", referencedColumnName="id")
     */
    protected $supplier_delivery;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $supplier_delivery_note_qty;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $supplier_delivery_qty_delivered;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=true)
     */
    protected $supplier_delivery_qty_to_store;

    /**
     * @ORM\OneToMany(targetEntity="StockLocationShelfProductSent", mappedBy="supplier_delivery_product")
     */
    protected $stock_location_shelf_product_sent;

    /**
     * @ORM\OneToMany(targetEntity="SupplierInvoiceProduct", mappedBy="supplier_delivery_product")
     */
    protected $supplier_invoice_product;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {

        //Supplier delievry note qty
        $metadata->addPropertyConstraint('supplier_delivery_note_qty', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_delivery_note_qty', new Assert\Range(array(
            'min'        => 1,
            'max'        => 2000,
            'minMessage' => 'The delivery note qty must be at least {{ limit }} characters length',
            'maxMessage' => 'The delivery note qty cannot be longer than {{ limit }} characters length',
        )));

        //Supplier delievry note qty delivered
        $metadata->addPropertyConstraint('supplier_delivery_qty_delivered', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_delivery_qty_delivered', new Assert\Range(array(
            'min'        => 1,
            'max'        => 2000,
            'minMessage' => 'The delivery note qty delivered must be at least {{ limit }} characters length',
            'maxMessage' => 'The delivery note qty delievred cannot be longer than {{ limit }} characters length',
        )));
    }




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stock_location_product_sent = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplier_invoice_product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set supplier_delivery_note_qty
     *
     * @param integer $supplierDeliveryNoteQty
     * @return SupplierDeliveryProduct
     */
    public function setSupplierDeliveryNoteQty($supplierDeliveryNoteQty)
    {
        $this->supplier_delivery_note_qty = $supplierDeliveryNoteQty;
    
        return $this;
    }

    /**
     * Get supplier_delivery_note_qty
     *
     * @return integer 
     */
    public function getSupplierDeliveryNoteQty()
    {
        return $this->supplier_delivery_note_qty;
    }

    /**
     * Set supplier_delivery_qty_delivered
     *
     * @param integer $supplierDeliveryQtyDelivered
     * @return SupplierDeliveryProduct
     */
    public function setSupplierDeliveryQtyDelivered($supplierDeliveryQtyDelivered)
    {
        $this->supplier_delivery_qty_delivered = $supplierDeliveryQtyDelivered;
    
        return $this;
    }

    /**
     * Get supplier_delivery_qty_delivered
     *
     * @return integer 
     */
    public function getSupplierDeliveryQtyDelivered()
    {
        return $this->supplier_delivery_qty_delivered;
    }

    /**
     * Get supplier_delivery_qty_remaining
     *
     * @return integer 
     */
    public function getSupplierDeliveryQtyRemaining()
    {
        return $this->supplier_delivery_note_qty - $this->supplier_delivery_qty_delivered;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return SupplierDeliveryProduct
     */
    public function setProduct(\MilesApart\AdminBundle\Entity\Product $product = null)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return \MilesApart\AdminBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set supplier_delivery
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery
     * @return SupplierDeliveryProduct
     */
    public function setSupplierDelivery(\MilesApart\AdminBundle\Entity\SupplierDelivery $supplierDelivery = null)
    {
        $this->supplier_delivery = $supplierDelivery;
    
        return $this;
    }

    /**
     * Get supplier_delivery
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierDelivery 
     */
    public function getSupplierDelivery()
    {
        return $this->supplier_delivery;
    }

    /**
     * Add stock_location_shelf_product_sent
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocationShelfProductSent $stockLocationShelfProductSent
     * @return SupplierDeliveryProduct
     */
    public function addStockLocationShelfProductSent(\MilesApart\AdminBundle\Entity\StockLocationShelfProductSent $stockLocationShelfProductSent)
    {
        $this->stock_location_shelf_product_sent[] = $stockLocationShelfProductSent;
    
        return $this;
    }

    /**
     * Remove stock_location_shelf_product_sent
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocationShelfProductSent $stockLocationShelfProductSent
     */
    public function removeStockLocationShelfProductSent(\MilesApart\AdminBundle\Entity\StockLocationShelfProductSent $stockLocationShelfProductSent)
    {
        $this->stock_location_shelf_product_sent->removeElement($stockLocationShelfProductSent);
    }

    /**
     * Get stock_location_shelf_product_sent
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStockLocationShelfProductSent()
    {
        return $this->stock_location_shelf_product_sent;
    }

    /**
     * Add supplier_invoice_product
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoiceProduct $supplierInvoiceProduct
     * @return SupplierDeliveryProduct
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
     * Get supplier_delivery_product_outers
     *
     * @return integer 
     */
    public function getSupplierDeliveryProductOuters()
    {
        if ($this->getProduct()->getProductOuterQuantity() != NULL) {

            $number_of_cases = $this->getSupplierDeliveryNoteQty() / $this->getProduct()->getProductOuterQuantity();

            if ($number_of_cases < 1) {
                $number_of_cases = "-";
            }
        } else {
            $number_of_cases = "-";
        }

        return $number_of_cases;
        
    }

    /**
     * Get supplier_delivery_product_inners
     *
     * @return integer 
     */
    public function getSupplierDeliveryProductInners()
    {
        if ($this->getProduct()->getProductInnerQuantity() != NULL) {

            $number_of_inners = $this->getSupplierDeliveryNoteQty() / $this->getProduct()->getProductInnerQuantity();

        } else {
            $number_of_inners = "-";
        }

        return $number_of_inners;
        
    }

    /**
     * Get supplier_delivery_product_outers_remaining
     *
     * @return integer 
     */
    public function getSupplierDeliveryProductOutersRemaining()
    {
        if ($this->getProduct()->getProductOuterQuantity() != NULL) {

            $number_of_cases = $this->getSupplierDeliveryQtyRemaining() / $this->getProduct()->getProductOuterQuantity();

            if ($number_of_cases < 1) {
                $number_of_cases = "-";
            }
        } else {
            $number_of_cases = "-";
        }

        return $number_of_cases;
        
    }

    /**
     * Get supplier_delivery_product_inners_remaining
     *
     * @return integer 
     */
    public function getSupplierDeliveryProductInnersRemaining()
    {
        if ($this->getProduct()->getProductInnerQuantity() != NULL) {

            $number_of_inners = $this->getSupplierDeliveryQtyRemaining() / $this->getProduct()->getProductInnerQuantity();

        } else {
            $number_of_inners = "-";
        }

        return $number_of_inners;
        
    }

    /**
     * Set supplier_delivery_qty_to_store
     *
     * @param integer $supplierDeliveryQtyToStore
     * @return SupplierDeliveryProduct
     */
    public function setSupplierDeliveryQtyToStore($supplierDeliveryQtyToStore)
    {
        $this->supplier_delivery_qty_to_store = $supplierDeliveryQtyToStore;

        return $this;
    }

    /**
     * Get supplier_delivery_qty_to_store
     *
     * @return integer 
     */
    public function getSupplierDeliveryQtyToStore()
    {
        return $this->supplier_delivery_qty_to_store;
    }
}
