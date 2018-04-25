<?php
// src/MilesApart/AdminBundle/Entity/StockLocationShelfProductSent.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\StockLocationShelfProductSentRepository")
 * @ORM\Table(name="stock_location_shelf_product_sent")
 * @ORM\HasLifecycleCallbacks()
 */

class StockLocationShelfProductSent
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
     * @ORM\ManyToOne(targetEntity="SupplierDeliveryProduct", inversedBy="stock_location_shelf_product_sent")
     * @ORM\JoinTable(name="supplier_delivery_product")
     * @ORM\JoinColumn(name="supplier_delivery_product_id", referencedColumnName="id")
     */
    protected $supplier_delivery_product;

    /**
     * @ORM\ManyToOne(targetEntity="StockLocationShelf", inversedBy="stock_location_shelf_product_sent")
     * @ORM\JoinTable(name="stock_location_shelf")
     * @ORM\JoinColumn(name="stock_location_shelf_id", referencedColumnName="id")
     */
    protected $stock_location_shelf;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $stock_location_shelf_product_sent_qty;



    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Stock location product sent qty
        $metadata->addPropertyConstraint('stock_location_shelf_product_sent_qty', new Assert\NotBlank());
        $metadata->addPropertyConstraint('stock_location_shelf_product_sent_qty', new Assert\Length(array(
            'min'        => 1,
            'max'        => 200,
            'minMessage' => 'The stock location product sent qty must be at least {{ limit }} characters length',
            'maxMessage' => 'The stock location product sent qty cannot be longer than {{ limit }} characters length',
        )));
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
     * Set stock_location_shelf_product_sent_qty
     *
     * @param integer $stockLocationShelfProductSentQty
     * @return StockLocationShelfProductSent
     */
    public function setStockLocationShelfProductSentQty($stockLocationShelfProductSentQty)
    {
        $this->stock_location_shelf_product_sent_qty = $stockLocationShelfProductSentQty;
    
        return $this;
    }

    /**
     * Get stock_location_shelf_product_sent_qty
     *
     * @return integer 
     */
    public function getStockLocationShelfProductSentQty()
    {
        return $this->stock_location_shelf_product_sent_qty;
    }

    /**
     * Set supplier_delivery_product
     *
     * @param \MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct
     * @return StockLocationShelfProductSent
     */
    public function setSupplierDeliveryProduct(\MilesApart\AdminBundle\Entity\SupplierDeliveryProduct $supplierDeliveryProduct = null)
    {
        $this->supplier_delivery_product = $supplierDeliveryProduct;
    
        return $this;
    }

    /**
     * Get supplier_delivery_product
     *
     * @return \MilesApart\AdminBundle\Entity\SupplierDeliveryProduct 
     */
    public function getSupplierDeliveryProduct()
    {
        return $this->supplier_delivery_product;
    }

    /**
     * Set stock_location_shelf
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocationShelf $stockLocationShelf
     * @return StockLocationShelfProductSent
     */
    public function setStockLocationShelf(\MilesApart\AdminBundle\Entity\StockLocationShelf $stockLocationShelf = null)
    {
        $this->stock_location_shelf = $stockLocationShelf;
    
        return $this;
    }

    /**
     * Get stock_location_shelf
     *
     * @return \MilesApart\AdminBundle\Entity\StockLocationShelf 
     */
    public function getStockLocationShelf()
    {
        return $this->stock_location_shelf;
    }
}