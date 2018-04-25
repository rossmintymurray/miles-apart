<?php
// src/MilesApart/StaffBundle/Entity/PackUpSeasonal.php -- Defines the pack up seasonal object

namespace MilesApart\StaffBundle\Entity\Products;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;


class SearchProduct
{
    //Define the values

    protected $product_name;

    protected $product_barcode;

    protected $product_supplier_code;

    /**
     * Set product_name
     *
     * @param string $productName
     * @return productName
     */
    public function setProductName($productName)
    {
        $this->product_name = $productName;
    
        return $this;
    }

    /**
     * Get product_name
     *
     * @return string 
     */
    public function getProductName()
    {
        return $this->product_name;
    }

    /**
     * Set product_barcode
     *
     * @param string $productBarcode
     * @return productBarcode
     */
    public function setProductBarcode($productBarcode)
    {
        $this->product_barcode = $productBarcode;
    
        return $this;
    }

    /**
     * Get product_barcode
     *
     * @return string 
     */
    public function getProductBarcode()
    {
        return $this->product_barcode;
    }

    /**
     * Set product_supplier_code
     *
     * @param string $productSupplierCode
     * @return productSupplierCode
     */
    public function setProductSupplierCode($productSupplierCode)
    {
        $this->product_supplier_code = $productSupplierCode;
    
        return $this;
    }

    /**
     * Get product_supplier_code
     *
     * @return string 
     */
    public function getProductSupplierCode()
    {
        return $this->product_supplier_code;
    }
}
