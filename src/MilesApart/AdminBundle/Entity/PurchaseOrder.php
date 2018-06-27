<?php
// src/MilesApart/AdminBundle/Entity/PurchaseOrder.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\PurchaseOrderRepository")
 * @ORM\Table(name="purchase_order")
 * @ORM\HasLifecycleCallbacks()
 */

class PurchaseOrder
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
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="purchase_order")
     * @ORM\JoinTable(name="supplier")
     * @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     */
    protected $supplier;

    /**
     * @ORM\ManyToOne(targetEntity="PurchaseOrderState", inversedBy="purchase_order")
     * @ORM\JoinTable(name="purchase_order_state")
     * @ORM\JoinColumn(name="purchase_order_state_id", referencedColumnName="id")
     */
    protected $purchase_order_state;

    /**
     * @ORM\Column(type="string", length=20, unique=false, nullable=true)
     */
    protected $supplier_purchase_order_code;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $purchase_order_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $purchase_order_date_modified;

    /**
     * @ORM\Column(type="date", unique=false, nullable=true)
     */
    protected $purchase_order_date_completed;

    /**
     * @ORM\OneToMany(targetEntity="PurchaseOrderProduct", mappedBy="purchase_order")
     */
    protected $purchase_order_product;

    /**
     * @ORM\OneToMany(targetEntity="SupplierInvoicePurchaseOrder", mappedBy="purchase_order")
     */
    protected $supplier_invoice_purchase_order;

    protected $purchase_order_current_total;
   
    protected $purchase_order_minimum_order_value_difference;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setPurchaseOrderDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getPurchaseOrderDateCreated() == null)
        {
            $this->setPurchaseOrderDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
    }



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Supplier
        $metadata->addPropertyConstraint('supplier', new Assert\Choice(array(
            'callback' => 'getSupplier',
        )));

        //Purchase order state
        $metadata->addPropertyConstraint('purchase_order_state', new Assert\Choice(array(
            'callback' => 'getPurchaseOrderState',
        )));

        //Supplier purchase order code
        $metadata->addPropertyConstraint('supplier_purchase_order_code', new Assert\NotBlank());
        $metadata->addPropertyConstraint('supplier_purchase_order_code', new Assert\Length(array(
            'min'        => 1,
            'max'        => 20,
            'minMessage' => 'The supplier purchase order code must be at least {{ limit }} characters length',
            'maxMessage' => 'The supplier purchase order code cannot be longer than {{ limit }} characters length',
        )));

        //Purchase order date completed
        $metadata->addPropertyConstraint('purchase_order_date_completed', new Assert\Date());
    }



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->purchase_order_product = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set supplier_purchase_order_code
     *
     * @param string $supplierPurchaseOrderCode
     * @return PurchaseOrder
     */
    public function setSupplierPurchaseOrderCode($supplierPurchaseOrderCode)
    {
        $this->supplier_purchase_order_code = $supplierPurchaseOrderCode;
    
        return $this;
    }

    /**
     * Get supplier_purchase_order_code
     *
     * @return string 
     */
    public function getSupplierPurchaseOrderCode()
    {
        return $this->supplier_purchase_order_code;
    }

    /**
     * Set purchase_order_date_created
     *
     * @param \DateTime $purchaseOrderDateCreated
     * @return PurchaseOrder
     */
    public function setPurchaseOrderDateCreated($purchaseOrderDateCreated)
    {
        $this->purchase_order_date_created = $purchaseOrderDateCreated;
    
        return $this;
    }

    /**
     * Get purchase_order_date_created
     *
     * @return \DateTime 
     */
    public function getPurchaseOrderDateCreated()
    {
        return $this->purchase_order_date_created;
    }

    /**
     * Set purchase_order_date_modified
     *
     * @param \DateTime $purchaseOrderDateModified
     * @return PurchaseOrder
     */
    public function setPurchaseOrderDateModified($purchaseOrderDateModified)
    {
        $this->purchase_order_date_modified = $purchaseOrderDateModified;
    
        return $this;
    }

    /**
     * Get purchase_order_date_modified
     *
     * @return \DateTime 
     */
    public function getPurchaseOrderDateModified()
    {
        return $this->purchase_order_date_modified;
    }

    /**
     * Set purchase_order_date_completed
     *
     * @param \DateTime $purchaseOrderDateCompleted
     * @return PurchaseOrder
     */
    public function setPurchaseOrderDateCompleted($purchaseOrderDateCompleted)
    {
        $this->purchase_order_date_completed = $purchaseOrderDateCompleted;
    
        return $this;
    }

    /**
     * Get purchase_order_date_completed
     *
     * @return \DateTime 
     */
    public function getPurchaseOrderDateCompleted()
    {
        return $this->purchase_order_date_completed;
    }

    /**
     * Set supplier
     *
     * @param \MilesApart\AdminBundle\Entity\Supplier $supplier
     * @return PurchaseOrder
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
     * Set purchase_order_state
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrderState $purchaseOrderState
     * @return PurchaseOrder
     */
    public function setPurchaseOrderState(\MilesApart\AdminBundle\Entity\PurchaseOrderState $purchaseOrderState = null)
    {
        $this->purchase_order_state = $purchaseOrderState;
    
        return $this;
    }

    /**
     * Get purchase_order_state
     *
     * @return \MilesApart\AdminBundle\Entity\PurchaseOrderState 
     */
    public function getPurchaseOrderState()
    {
        return $this->purchase_order_state;
    }

    /**
     * Add purchase_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrderProduct $purchaseOrderProduct
     * @return PurchaseOrder
     */
    public function addPurchaseOrderProduct(\MilesApart\AdminBundle\Entity\PurchaseOrderProduct $purchaseOrderProduct)
    {
        $this->purchase_order_product[] = $purchaseOrderProduct;
    
        return $this;
    }

    /**
     * Remove purchase_order_product
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrderProduct $purchaseOrderProduct
     */
    public function removePurchaseOrderProduct(\MilesApart\AdminBundle\Entity\PurchaseOrderProduct $purchaseOrderProduct)
    {
        $this->purchase_order_product->removeElement($purchaseOrderProduct);
    }

    /**
     * Get purchase_order_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPurchaseOrderProduct()
    {
        return $this->purchase_order_product;
    }

    /**
     * Add supplier_invoice_purchase_order
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoicePurchaseOrder $supplierInvoicePurchaseOrder
     * @return PurchaseOrder
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

    /**
     * Get purchase_order_current_total
     *
     * @return float 
     */
    public function getPurchaseOrderCurrentTotal()
    {
        $purchase_order_total = 0;
        if (count($this->getPurchaseOrderProduct()) > 0) {
            foreach($this->getPurchaseOrderProduct() as $key => $value) {
                $purchase_order_total = $purchase_order_total + ($value->getProduct()->getCurrentCostDecimal() * $value->getPurchaseOrderProductQuantity());
            }
        } else {
            $purchase_order_total = "N/A";
        }

        return $purchase_order_total;

    }

    /**
     * Get purchase_order_current_total_outers
     *
     * @return float 
     */
    public function getPurchaseOrderCurrentTotalOuters()
    {
        $purchase_order_total_outers = 0;
        if (count($this->getPurchaseOrderProduct()) > 0) {
            foreach($this->getPurchaseOrderProduct() as $key => $value) {
                if ($value->getProduct()->getProductOuterQuantity() > 0) {
                    $purchase_order_total_outers = $purchase_order_total_outers + ($value->getPurchaseOrderProductQuantity() / $value->getProduct()->getProductOuterQuantity());
                } else {
                    $purchase_order_total_outers = $purchase_order_total_outers + $value->getPurchaseOrderProductQuantity();
                }
            }
        } else {
            $purchase_order_total = "N/A";
        }

        return $purchase_order_total_outers;

    }

    /**
     * Get purchase_order_current_total_inners
     *
     * @return float 
     */
    public function getPurchaseOrderCurrentTotalInners()
    {
        $purchase_order_total_inners = 0;
        if (count($this->getPurchaseOrderProduct()) > 0) {
            foreach($this->getPurchaseOrderProduct() as $key => $value) {
                if ($value->getProduct()->getProductInnerQuantity() > 0) {
                    $purchase_order_total_inners = $purchase_order_total_inners + ($value->getPurchaseInnerProductQuantity() / $value->getProduct()->getProductInnerQuantity());
                } else {
                    $purchase_order_total_inners = $purchase_order_total_inners + $value->getPurchaseOrderProductQuantity();
                }
            }
        } else {
            $purchase_order_total = "N/A";
        }

        return $purchase_order_total_inners;

    }

    /**
     * Get purchase_order_current_total_display
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPurchaseOrderCurrentTotalDisplay()
    {
        $current_total = $this->getPurchaseOrderCurrentTotal();

        if ($current_total < 1 && $current_total > 0) {
            $current_total = $current_total * 100;
            $current_total = $current_total . "p";
        } else if ($current_total >= 1) {
            $current_total = "£" . $current_total;
        } else {
            $current_total = "N/A";
        }
        return $current_total;
    
    }

    /**
     * Get purchase_order_minimum_order_value_difference
     *
     * @return float 
     */
    public function getPurchaseOrderMinimumOrderValueDifference()
    {
        //Check if supplier exists
        if ($this->getSupplier() != NULL) {
            if($this->getPurchaseOrderCurrentTotal() > 0 && $this->getSupplier()->getSupplierMinimumOrderValue() > 0) {
                $difference = $this->getPurchaseOrderCurrentTotal() - $this->getSupplier()->getSupplierMinimumOrderValue();
            } else {
                $difference = "N/A";
            }
        } else { 
            $difference = "N/A";
        }
        return $difference;
    }

    /**
     * Get purchase_order_current_total_display
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPurchaseOrderMinimumOrderValueDifferenceDisplay()
    {
        $difference = $this->getPurchaseOrderMinimumOrderValueDifference();

        if ($difference < 1 && $difference > 0) {
            $difference = $difference * 100;
            $difference = $difference . "p";
        } else if ($difference >= 1) {
            $difference = "£" . $difference;
        } else {
            $difference = "N/A";
        }
        return $difference;
    
    }

    /**
     * Get total_products
     *
     * @return string 
     */
    public function getTotalProducts()
    {

        return count($this->getPurchaseOrderProduct());
    }

}