<?php
// src/MilesApart/StaffBundle/Entity/PurchaseOrders/SelectSupplier.php 

namespace MilesApart\StaffBundle\Entity\PurchaseOrders;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;


class PurchaseOrderConfirmationManualInput
{
    //Define the values

    protected $product_supplier_code;
    protected $product_name;
    protected $product_barcode;
    protected $product_outer_quantity;
    protected $product_inner_quantity;
    protected $purchase_order_product_quantity;
    protected $product_cost;
    protected $total_cost;


    /**
     * Set product_supplier_code
     */
    public function setProductSupplierCode($productSupplierCode)
    {
        $this->product_supplier_code = $productSupplierCode;
    
        return $this;
    }

    /**
     * Get product_supplier_code
     */
    public function getProductSupplierCode()
    {
        return $this->product_supplier_code;
    }

   /**
     * Set product_name
     */
    public function setProductName($productName)
    {
        $this->product_name = $productName;
    
        return $this;
    }

    /**
     * Get product_name
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Set product_barcode
     */
    public function setProductBarcode($productBarcode)
    {
        $this->product_barcode = $productBarcode;
    
        return $this;
    }

    /**
     * Get product_barcode
     */
    public function getProductBarcode()
    {
        return $this->product_barcode;
    }

    /**
     * Set product_outer_quantity
     */
    public function setProductOuterQuantity($productOuterQuantity)
    {
        $this->product_outer_quantity = $productOuterQuantity;
    
        return $this;
    }

    /**
     * Get product_outer_quantity
     */
    public function getProductOuterQuantity()
    {
        return $this->product_outer_quantity;
    }

    /**
     * Set product_inner_quantity
     */
    public function setProductInnerQuantity($productInnerQuantity)
    {
        $this->product_inner_quantity = $productInnerQuantity;
    
        return $this;
    }

    /**
     * Get product_inner_quantity
     */
    public function getProductInnerQuantity()
    {
        return $this->product_inner_quantity;
    }

    /**
     * Set purchase_order_product_quantity
     */
    public function setPurchaseOrderProductQuantity($purchaseOrderProductQuantity)
    {
        $this->purchase_order_product_quantity = $purchaseOrderProductQuantity;
    
        return $this;
    }

    /**
     * Get purchase_order_product_quantity
     */
    public function getPurchaseOrderProductQuantity()
    {
        return $this->purchase_order_product_quantity;
    }

    /**
     * Set product_cost
     */
    public function setProductCost($productCost)
    {
        $this->product_cost = $productCost;
    
        return $this;
    }

    /**
     * Get product_cost
     */
    public function getProductCost()
    {
        return $this->product_cost;
    }

    /**
     * Set total_cost
     */
    public function setTotalCost($totalCost)
    {
        $this->total_cost = $totalCost;
    
        return $this;
    }

    /**
     * Get total_cost
     */
    public function getTotalCost()
    {
        return $this->total_cost;
    }
}