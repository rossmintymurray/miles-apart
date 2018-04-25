<?php
// src/MilesApart/AdminBundle/Entity/SupplierInvoicePurchaseOrder.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\SupplierInvoicePurchaseOrderRepository")
 * @ORM\Table(name="supplier_invoice_purchase_order")
 * @ORM\HasLifecycleCallbacks()
 */

class SupplierInvoicePurchaseOrder
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
     * @ORM\ManyToOne(targetEntity="SupplierInvoice", inversedBy="supplier_invoice_purchase_order")
     * @ORM\JoinTable(name="supplier_invoice")
     * @ORM\JoinColumn(name="supplier_invoice_id", referencedColumnName="id")
     */
    protected $supplier_invoice;

    /**
     * @ORM\ManyToOne(targetEntity="PurchaseOrder", inversedBy="supplier_invoice_purchase_order")
     * @ORM\JoinTable(name="purchase_order")
     * @ORM\JoinColumn(name="purcahse_order_id", referencedColumnName="id")
     */
    protected $purchase_order;

   

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        
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
     * Set supplier_invoice
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice
     * @return SupplierInvoicePurchaseOrder
     */
    public function setSupplierInvoice(\MilesApart\AdminBundle\Entity\SupplierInvoice $supplierInvoice = null)
    {
        $this->supplier_invoice = $supplierInvoice;
    
        return $this;
    }

    /**
     * Get supplier_invoice
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierInvoice 
     */
    public function getSupplierInvoice()
    {
        return $this->supplier_invoice;
    }

    /**
     * Set purchase_order
     *
     * @param \MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder
     * @return SupplierInvoicePurchaseOrder
     */
    public function setPurchaseOrder(\MilesApart\AdminBundle\Entity\PurchaseOrder $purchaseOrder = null)
    {
        $this->purchase_order = $purchaseOrder;
    
        return $this;
    }

    /**
     * Get purchase_order
     *
     * @return \MilesApart\AdminBundle\Entity\PurchaseOrder 
     */
    public function getPurchaseOrder()
    {
        return $this->purchase_order;
    }
}