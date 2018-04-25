<?php
// src/MilesApart/AdminBundle/Entity/StocktakeProduct.php -- Defines the customer type object

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\StocktakeProductRepository")
 * @ORM\Table(name="stocktake_product")
 * @ORM\HasLifecycleCallbacks()
 */

class StocktakeProduct
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
     * @ORM\ManyToOne(targetEntity="Stocktake", inversedBy="stocktake_product", cascade={"persist"})
     * @ORM\JoinTable(name="stocktake")
     * @ORM\JoinColumn(name="stocktake_id", referencedColumnName="id")
     */
    protected $stocktake;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="stocktake_product", cascade={"persist"})
     * @ORM\JoinTable(name="product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\ManyToOne(targetEntity="StockLocationShelf", inversedBy="stocktake_product")
     * @ORM\JoinTable(name="stock_location_shelf")
     * @ORM\JoinColumn(name="stock_location_shelf_id", referencedColumnName="id")
     */
    protected $stock_location_shelf;

    /**
     * @ORM\Column(type="integer", unique=false, nullable=false)
     */
    protected $stocktake_product_qty;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $stocktake_product_date_created;

    /**
     * @ORM\Column(type="datetime", unique=false, nullable=false)
     */
    protected $stocktake_product_date_modified;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setStocktakeProductDateModified(new \DateTime(date('Y-m-d H:i:s')));

        if($this->getStocktakeProductDateCreated() == null)
        {
            $this->setStocktakeProductDateCreated(new \DateTime(date('Y-m-d H:i:s')));
        }
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
     * Set stocktake_product_qty
     *
     * @param integer $stocktakeProductQty
     * @return StocktakeProduct
     */
    public function setStocktakeProductQty($stocktakeProductQty)
    {
        $this->stocktake_product_qty = $stocktakeProductQty;
    
        return $this;
    }

    /**
     * Get stocktake_product_qty
     *
     * @return integer 
     */
    public function getStocktakeProductQty()
    {
        return $this->stocktake_product_qty;
    }

    /**
     * Set stocktake_product_date_created
     *
     * @param \DateTime $stocktakeProductDateCreated
     * @return StocktakeProduct
     */
    public function setStocktakeProductDateCreated($stocktakeProductDateCreated)
    {
        $this->stocktake_product_date_created = $stocktakeProductDateCreated;
    
        return $this;
    }

    /**
     * Get stocktake_product_date_created
     *
     * @return \DateTime 
     */
    public function getStocktakeProductDateCreated()
    {
        return $this->stocktake_product_date_created;
    }

    /**
     * Set stocktake_product_date_modified
     *
     * @param \DateTime $stocktakeProductDateModified
     * @return StocktakeProduct
     */
    public function setStocktakeProductDateModified($stocktakeProductDateModified)
    {
        $this->stocktake_product_date_modified = $stocktakeProductDateModified;
    
        return $this;
    }

    /**
     * Get stocktake_product_date_modified
     *
     * @return \DateTime 
     */
    public function getStocktakeProductDateModified()
    {
        return $this->stocktake_product_date_modified;
    }

    /**
     * Set stocktake
     *
     * @param \MilesApart\AdminBundle\Entity\Stocktake $stocktake
     * @return StocktakeProduct
     */
    public function setStocktake(\MilesApart\AdminBundle\Entity\Stocktake $stocktake = null)
    {
        $this->stocktake = $stocktake;
    
        return $this;
    }

    /**
     * Get stocktake
     *
     * @return \MilesApart\AdminBundle\Entity\Stocktake 
     */
    public function getStocktake()
    {
        return $this->stocktake;
    }

    /**
     * Set product
     *
     * @param \MilesApart\AdminBundle\Entity\Product $product
     * @return StocktakeProduct
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
     * Set stock_location_shelf
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocationShelf $stockLocationShelf
     * @return StocktakeProduct
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