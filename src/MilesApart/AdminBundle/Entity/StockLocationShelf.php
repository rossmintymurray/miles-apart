<?php
// src/MilesApart/AdminBundle/Entity/StockLocationShelf.php -- Defines the stock location object (for storage of products)

namespace MilesApart\AdminBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity(repositoryClass="MilesApart\AdminBundle\Entity\Repository\StockLocationShelfRepository")
 * @ORM\Table(name="stock_location_shelf")
 * @ORM\HasLifecycleCallbacks()
 */

class StockLocationShelf
{
    /**
     *  
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true, nullable=false)
     */
    protected $stock_location_shelf_code;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $stock_location_shelf_code_printed;


    /**
     * @ORM\ManyToOne(targetEntity="StockLocation", inversedBy="stock_location_shelf")
     * @ORM\JoinTable(name="stock_location")
     * @ORM\JoinColumn(name="stock_location_id", referencedColumnName="id")
     */
    protected $stock_location;

    /**
     * @ORM\OneToMany(targetEntity="StockLocationShelfProductSent", mappedBy="stock_location_shelf")
     */
    protected $stock_location_shelf_product_sent;

    /**
     * @ORM\OneToMany(targetEntity="StocktakeProduct", mappedBy="stock_location_shelf")
     */
    protected $stocktake_product;

    /**
     * @ORM\OneToMany(targetEntity="StocktakeSeasonalStorageBox", mappedBy="stock_location_shelf", cascade={"persist"})
     */
    protected $stocktake_seasonal_storage_box;

    protected $stock_location_shelf_wall;
    

    //Validators for data
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        //Stock location name
        $metadata->addPropertyConstraint('stock_location_shelf_code', new Assert\NotBlank());
        $metadata->addPropertyConstraint('stock_location_shelf_code', new Assert\Length(array(
            'min'        => 4,
            'max'        => 20,
            'minMessage' => 'The stock location name must be at least {{ limit }} characters length',
            'maxMessage' => 'YThe stock location name cannot be longer than {{ limit }} characters length',
        )));

    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stock_location_shelf_product_sent = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stocktake_product = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stocktake_seasonal_storage_box = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stock_location_shelf_code_printed = false;
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
     * Set stock_location_shelf_code
     *
     * @param string $stockLocationShelfCode
     * @return StockLocationShelf
     */
    public function setStockLocationShelfCode($stockLocationShelfCode)
    {
        $this->stock_location_shelf_code = $stockLocationShelfCode;
    
        return $this;
    }

    /**
     * Get stock_location_shelf_code
     *
     * @return string 
     */
    public function getStockLocationShelfCode()
    {
        return $this->stock_location_shelf_code;
    }

    /**
     * Set stock_location_shelf_code_printed
     *
     * @param boolean $stockLocationShelfCodePrinted
     * @return StockLocationShelf
     */
    public function setStockLocationShelfCodePrinted($stockLocationShelfCodePrinted)
    {
        $this->stock_location_shelf_code_printed = $stockLocationShelfCodePrinted;
    
        return $this;
    }

    /**
     * Get stock_location_shelf_code_printed
     *
     * @return boolean 
     */
    public function getStockLocationShelfCodePrinted()
    {
        return $this->stock_location_shelf_code_printed;
    }

    /**
     * Set stock_location_wall
     *
     * @param string $stockLocationShelfWall
     * @return StockLocationShelf
     */
    public function setStockLocationShelfWall($stockLocationShelfWall)
    {
        $this->stock_location_shelf_wall = $stockLocationShelfWall;
    
        return $this;
    }

    /**
     * Get stock_location_shelf_wall
     *
     * @return string 
     */
    public function getStockLocationShelfWall()
    {
        return $this->stock_location_shelf_wall;
    }

    /**
     * Set stock_location
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocation $stockLocation
     * @return StockLocationShelf
     */
    public function setStockLocation(\MilesApart\AdminBundle\Entity\StockLocation $stockLocation = null)
    {
        $this->stock_location = $stockLocation;
    
        return $this;
    }

    /**
     * Get stock_location
     *
     * @return \MilesApart\AdminBundle\Entity\StockLocation 
     */
    public function getStockLocation()
    {
        return $this->stock_location;
    }

    /**
     * Add stock_location_shelf_product_sent
     *
     * @param \MilesApart\AdminBundle\Entity\StockLocationShelfProductSent $stockLocationShelfProductSent
     * @return StockLocationShelf
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
     * Add stocktake_product
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct
     * @return StockLocationShelf
     */
    public function addStocktakeProduct(\MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct)
    {
        $this->stocktake_product[] = $stocktakeProduct;
    
        return $this;
    }

    /**
     * Remove stocktake_product
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct
     */
    public function removeStocktakeProduct(\MilesApart\AdminBundle\Entity\StocktakeProduct $stocktakeProduct)
    {
        $this->stocktake_product->removeElement($stocktakeProduct);
    }

    /**
     * Get stocktake_product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStocktakeProduct()
    {
        return $this->stocktake_product;
    }

    /**
     * Add stocktake_seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeSeasonalStorageBox $stocktakeSeasonalStorageBox
     * @return StockLocationShelf
     */
    public function addStocktakeSeasonalStorageBox(\MilesApart\AdminBundle\Entity\StocktakeSeasonalStorageBox $stocktakeSeasonalStorageBox)
    {
        $this->stocktake_seasonal_storage_box[] = $stocktakeSeasonalStorageBox;
    
        return $this;
    }

    /**
     * Remove stocktake_seasonal_storage_box
     *
     * @param \MilesApart\AdminBundle\Entity\StocktakeSeasonalStorageBox $stocktakeSeasonalStorageBox
     */
    public function removeStocktakeSeasonalStorageBox(\MilesApart\AdminBundle\Entity\StocktakeSeasonalStorageBox $stocktakeSeasonalStorageBox)
    {
        $this->stocktake_seasonal_storage_box->removeElement($stocktakeSeasonalStorageBox);
    }

    /**
     * Get stocktake_seasonal_storage_box
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStocktakeSeasonalStorageBox()
    {
        return $this->stocktake_seasonal_storage_box;
    }
}