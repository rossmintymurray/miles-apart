<?php
// src/MilesApart/StaffBundle/Entity/PurchaseOrders/SelectSupplier.php 

namespace MilesApart\StaffBundle\Entity\PurchaseOrders;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;


class SelectSupplier
{
    //Define the values

    protected $supplier;


    /**
     * Set supplier
     *
     * @param string $supplier
     * @return supplier
     */
    public function setSupplier($supplier)
    {
        $this->supplier = $supplier;
    
        return $this;
    }

    /**
     * Get supplier
     *
     * @return string 
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set supplier id
     *
     * @param string $supplier
     * @return supplier
     */
    public function setSupplierId($supplierId)
    {
        $this->supplier_id = $supplierId;
    
        return $this;
    }

    /**
     * Get supplier id
     *
     * @return string 
     */
    public function getSupplierId()
    {
        return $this->supplier_id;
    }

}
